<?php

namespace App\Repositories;

use App\Models\File;
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
        $model->public_key = $inputs['public_key'];
        $model->private_key = $inputs['private_key'];
        $model->price = $inputs['price'];
        $model->sender_id = $inputs['sender_id'];
        $model->recipient_id = $inputs['recipient_id'];

        $model->save();
        return $model->id;
    }

}
