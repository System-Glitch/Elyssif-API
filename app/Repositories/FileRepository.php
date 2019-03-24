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
     * Get a paginate of the given user's sent files.
     * @param User $user
     * @param string $search
     * @param  int  $n the amount of recordings per page
     * @param  array|mixed  $columns the columns to select with optional alias, defaults to '*'
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getSentFilesPaginate(User $user, $search = null, int $n = ResourceRepository::AMOUNT_PER_PAGE, $columns = ['*']) {
        $files = $user->sentFiles();
        if($search) {
            $files = $files->where('name', 'LIKE', '%'.$this->escape_like($search).'%');
        }

        return $files->select($columns)->paginate($n);
    }

    /**
     * Get a paginate of the given user's received files.
     * @param User $user
     * @param string $search
     * @param  int  $n the amount of recordings per page
     * @param  array|mixed  $columns the columns to select with optional alias, defaults to '*'
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getReceivedFilesPaginate(User $user, $search = null, int $n = ResourceRepository::AMOUNT_PER_PAGE, $columns = ['*']) {
        $files = $user->receivedFiles();
        if($search) {
            $files = $files->where('name', 'LIKE', '%'.$this->escape_like($search).'%');
        }

        return $files->select($columns)->paginate($n);
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
                    ->where('hash_ciphered', $cipheredHash)
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
                    ->where('hash_ciphered', $cipheredHash)
                    ->select('private_key')->first();
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
        $model->hash = $inputs['hash'];

        $model->public_key = $inputs['public_key'];
        $model->private_key = $inputs['private_key'];


        if(isset($inputs['price'])) $model->price = $inputs['price'];
        $model->sender_id = $inputs['sender_id'];
        $model->recipient_id = $inputs['recipient_id'];

        $model->save();
        return $model->id;
    }

}
