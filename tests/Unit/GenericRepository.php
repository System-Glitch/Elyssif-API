<?php
/*
 * Elyssif-API
 * Copyright (C) 2019 Jérémy LAMBERT (System-Glitch)
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.

 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.

 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <https://www.gnu.org/licenses/>.
 */

namespace Tests\Unit;

use App\Repositories\ResourceRepository;
use Illuminate\Database\Eloquent\Model;

class GenericRepository extends ResourceRepository
{

    /**
     * Create a new repository instance.
     *
     * @param \Tests\Unit\GenericModel $model
     * @return void
     */
    public function __construct(GenericModel $model)
    {
        $this->model = $model;
    }

    /**
     * Resource relative behavior for saving a record.
     *
     * @param \Illuminate\Database\Eloquent\Model $model
     * @param array $inputs
     * @return int the id of the saved resource
     */
    protected function save(Model $model, Array $inputs)
    {
        $model->name   = $inputs['name'];
        $model->number = $inputs['number'];

        $model->save();
        return $model->id;
    }
}