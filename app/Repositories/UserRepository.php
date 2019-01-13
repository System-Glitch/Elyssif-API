<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;
use App\Repositories\ResourceRepository;
use App\Models\User;

class UserRepository extends ResourceRepository
{

    /**
     * Create a new repository instance.
     *
     * @param  \App\Models\User  $model
     * @return void
     */
    public function __construct(User $model)
    {
        $this->model = $model;
    }

    /**
     * Resource relative behavior for saving a record.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @param  array  $inputs
     * @return int id, the id of the saved resource
     */
    protected function save(Model $model, Array $inputs)
    {
        $model->name = $inputs['name'];
        $model->email = $inputs['email'];
        if(isset($inputs['password'])) $model->password = $inputs['password'];

        $model->save();
        return $model->id;
    }

}
