<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

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
     * @return int  the id of the saved resource
     */
    protected function save(Model $model, Array $inputs)
    {
        $model->name = $inputs['name'];
        $model->email = $inputs['email'];
        if(isset($inputs['password'])) $model->password = $inputs['password'];
        if(isset($inputs['address'])) $model->address = $inputs['address'];

        $model->save();
        return $model->id;
    }

    // TODO update if address set to null then set price of all sent files to zero

}
