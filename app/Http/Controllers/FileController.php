<?php

namespace App\Http\Controllers;

use App\Http\Requests\SearchRequest;
use App\Http\Requests\Files\FileCheckRequest;
use App\Http\Requests\Files\FileCipherRequest;
use App\Http\Requests\Files\FileCreateRequest;
use App\Http\Requests\Files\FileFetchRequest;
use App\Http\Requests\Files\FileUpdateRequest;
use App\Jobs\SendToAddress;
use App\Models\File;
use App\Repositories\FileRepository;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Elliptic\EC;
use App\Events\UserNotification;

class FileController extends Controller
{

    /**
     *
     * @var \App\Repositories\FileRepository
     */
    private $fileRepository;

    /**
     * Create a new controller instance.
     *
     * @param  \App\Repositories\FileRepository  fileRepository
     * @return void
     */
    public function __construct(FileRepository $fileRepository)
    {
        $this->fileRepository = $fileRepository;
    }

    /**
     * Display a listing of the files sent by the current user.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function indexSent(SearchRequest $request)
    {
        return $this->fileRepository->getSentFilesPaginate($request->user(), $request->input('search'));
    }

    /**
     * Display a listing of the files sent to the current user.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function indexReceived(SearchRequest $request)
    {
        return $this->fileRepository->getReceivedFilesPaginate($request->user(), $request->input('search'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\Files\FileCreateRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(FileCreateRequest $request)
    {
        // Check if file already exists but not encrypted yet
        $file = $this->fileRepository->getUnencrypted($request->user()->id, $request->input('name'), $request->input('hash'));

        if($file == null) {
            $inputs = $request->only(['name', 'recipient_id', 'hash', 'price']);
            $inputs['sender_id'] = $request->user()->id;

            $keyPair = $this->generateKeys();

            $inputs['public_key'] = $keyPair->getPublic(false,'hex');
            $inputs['private_key'] = $keyPair->getPrivate('hex');

            if($request->has('price') && $request->input('price') > 0) {
                $inputs['address'] = bitcoind()->getNewAddress()->result();
            }

            $file = $this->fileRepository->store($inputs);
        }

        return response()->json([
            'id' => $file->id,
            'public_key' => $file->public_key
        ], Response::HTTP_CREATED);
    }

    /**
     * Generate Keys using the "secp256k1" Elliptic Curve method
     *
     * @return mixed
     */
    public function generateKeys()
    {
        return (new EC('secp256k1'))->genKeyPair();
    }

    /**
     * Set the ciphered hash of the specified resource
     *
     * @param  \App\Http\Requests\Files\FileCipherRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function cipher(FileCipherRequest $request, File $file)
    {
        if($request->user()->id != $file->sender_id || !empty($file->ciphered_at)) {
            return new Response('', Response::HTTP_FORBIDDEN);
        }

        $this->fileRepository->update($file, [
            'ciphered_at' => now(),
            'hash_ciphered' => $request->input('ciphered_hash')
        ]);

        event(new UserNotification($file->recipient_id, '%file-received', $file->name));

        return new Response('', Response::HTTP_NO_CONTENT);
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\File  $file
     * @return \App\Models\File
     */
    public function show(File $file)
    {
        return $file->sender_id == request()->user()->id ? $file : new Response('', Response::HTTP_FORBIDDEN);
    }

    /**
     * Display the payment state of the specified file.
     *
     * @param \App\Models\File  $file
     * @return array
     */
    public function paymentState(File $file)
    {
        return $file->recipient_id == request()->user()->id ? $this->fileRepository->getPaymentState($file->id) : new Response('', Response::HTTP_FORBIDDEN);
    }

    /**
     * Update the specified resource in storage.
     * CURRENTLY DISABLED
     *
     * @param  \App\Http\Requests\Files\FileUpdateRequest  $request
     * @param  \App\Models\File  $file
     * @return \Illuminate\Http\Response
     */
    public function update(FileUpdateRequest $request, File $file)
    {
        if($request->user()->id != $file->sender_id || !empty($file->deciphered_at)) {
            return new Response('', Response::HTTP_FORBIDDEN);
        }
        
        $inputs = [];
        if(!empty($request->input('name'))) $inputs['name'] = $request->input('name');
        if($request->has('price')) $inputs['price'] = $request->input('price');

        if(count($inputs)) {
            $this->fileRepository->update($file, $inputs);
        }
        
        return new Response('', Response::HTTP_NO_CONTENT);
    }

    /**
     * Fetch a file by its ciphered hash.
     *
     * @param  \App\Http\Requests\Files\FileFetchRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function fetch(FileFetchRequest $request)
    {
        $file = $this->fileRepository->getFileForFetch($request->user(), $request->input('ciphered_hash'));

        if ($file != null) {
            return $file->makeVisible('address')->makeHidden('recipient_id')->toArray();
        } else {
            return new Response('', Response::HTTP_NOT_FOUND);
        }
    }

    /**
     * Get the private key for the given file.
     *
     * @param File $file
     * @return \Illuminate\Http\Response
     */
    public function key(File $file)
    {
        if(request()->user()->id != $file->recipient_id) {
            return new Response('', Response::HTTP_FORBIDDEN);
        }

        if($file->price > 0) {
            $paymentState = $this->fileRepository->getPaymentState($file);
            if($paymentState['confirmed'] < $file->price) {
                return new Response('', Response::HTTP_FORBIDDEN);
            }
        }

        return response()->json($file->private_key);
    }

    /**
     * Check a file by comparing ciphered and deciphered hashes.
     *
     * @param  \App\Http\Requests\Files\FileCheckRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function check(FileCheckRequest $request)
    {
        $file = $this->fileRepository->getFileForCheck($request->user(), $request->input('ciphered_hash'), $request->input('hash'));

        if($file != null && empty($file->deciphered_at)) {
            $this->fileRepository->update($file, ['deciphered_at' => now()]);

            if(checkBitcoinAddress($file->sender->address)){
                dispatch( new SendToAddress($file->sender->address, $file->price - floatval(env('ELYSSIF_FEES', 0.0004)), false));
            }else{
                Log::info('User '.$file->sender->id.' doesn\'t have a valid address (\''.$file->sender->address.'\'). Payment aborted.');
            }
        }
                                
        return new Response('', $file != null ? Response::HTTP_NO_CONTENT : Response::HTTP_NOT_FOUND);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\File  $file
     * @return \Illuminate\Http\Response
     */
    public function destroy(File $file)
    {
        if(request()->user()->id != $file->sender_id) {
            return new Response('', Response::HTTP_FORBIDDEN);
        }

        if($file->address) {
            $paymentState = $this->fileRepository->getPaymentState($file);

            if($paymentState['confirmed'] > 0 || $paymentState['pending'] > 0) {
                return new Response('file-paid', Response::HTTP_FORBIDDEN);
            }
        }

        $this->fileRepository->destroy($file);
        return new Response('', Response::HTTP_NO_CONTENT);
    }
}
