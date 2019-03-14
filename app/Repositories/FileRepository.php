<?php

namespace App\Repositories;

use App\Models\File;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class FileRepository extends ResourceRepository
{

    /**
     * Create a new repository instance.
     *
     * @param  \App\Models\File  $model
     * @return void
     */
    public function __construct(File $model)
    {
        $this->model = $model;
    }

    /**
     * Get a file matching the given hashes
     * from the given user's received files.
     * @param \App\Models\User  $user
     * @param string  $cipheredHash
     * @param string  $hash
     * @return \App\Models\File
     */
    public function getFileForCheck(User $user, $cipheredHash, $hash)
    {
        return $user->receivedFiles()
                    ->where('ciphered_hash', $cipheredHash)
                    ->where('hash', $hash)
                    ->first();
    }

    /**
     * Get a file matching the given ciphered hash
     * from the given user's received files. Only
     * the "public_key" column is selected.
     * @param \App\Models\User  $user
     * @param string  $cipheredHash
     * @param string  $hash
     * @return \App\Models\File
     */
    public function getFileForFetch(User $user, $cipheredHash)
    {
        return $user->receivedFiles()
                    ->where('ciphered_hash', $cipheredHash)
                    ->select('public_key')->first();
    }

    /**
     * Resource relative behavior for saving a record.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @param  array  $inputs
     * @return int  the id of the saved resource
     */
    protected function save(Model $model, Array $inputs)
    {
        $model->name = $inputs['name'];
        $model->ciphered_at = $inputs['ciphered_at'];
        $model->deciphered_at = $inputs['deciphered_at'];
        $model->hash = $inputs['hash'];
        $model->hash_ciphered = $inputs['hash_ciphered'];
        $model->public_key = $inputs['public_key']; // TODO generate keys
        $model->private_key = $inputs['private_key'];
        $model->price = $inputs['price'];
        $model->sender_id = $inputs['sender_id'];
        $model->recipient_id = $inputs['recipient_id'];

        $model->save();
        return $model->id;
    }

}
