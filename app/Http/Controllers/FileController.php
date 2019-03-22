<?php

namespace App\Http\Controllers;

use App\Http\Requests\SearchRequest;
use App\Http\Requests\Files\FileCheckRequest;
use App\Http\Requests\Files\FileCipherRequest;
use App\Http\Requests\Files\FileCreateRequest;
use App\Http\Requests\Files\FileFetchRequest;
use App\Http\Requests\Files\FileUpdateRequest;
use App\Models\File;
use App\Repositories\FileRepository;
use Illuminate\Http\Response;

class FileController extends Controller
{

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
        $inputs = $request->only(['name', 'recipient_id', 'hash', 'price']);
        $inputs->put('sender_id', $request->user()->id);
        $file = $this->fileRepository->store($inputs);
        return response()->json($file->private_key, Response.HTTP_CREATED);
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
            'ciphered_hash' => $request->input('ciphered_hash')
        ]);
        
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
     * Update the specified resource in storage.
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
        if(!empty($request->input('name'))) $inputs[] = $request->input('name');
        if(!empty($request->input('price'))) $inputs[] = $request->input('price');
        
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

        if($file != null) {
            return response()->json($file->public_key);
        } else {
            return new Response('', Response::HTTP_NOT_FOUND);
        }
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

        if($file != null) {
            $this->fileRepository->update($file, ['deciphered_at' => now()]);
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
        
        $this->fileRepository->destroy(file);
        return new Response('', Response::HTTP_NO_CONTENT);
    }
}
