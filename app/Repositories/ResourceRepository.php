<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;

abstract class ResourceRepository
{

    protected $model;

    /**
     * Get the model this repository is using
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * Get all the existing non-deleted recordings.
     *
     * @return array
     */
    public function getAll()
    {
        return $this->model->all();
    }

    /**
     * Get all the deleted recordings.
     *
     * @param  bool  $only, select only the deleted ones if true, select all existing records if set to false. Defaults to true.
     * @return array
     */
    public function getAllTrashed($only = true)
    {
        return $only ? 
            $this->getAll()->onlyTrashed() :
            $this->getAll()->withTrashed();
    }

    /**
     * Get all the existing recordings ordered according to the given column.
     *
     * @param  string  $orderColumn
     * @param  string  $order (ex.: 'asc', 'desc')
     * @return array
     */
    public function getAllOrdered($orderColumn, $order)
    {
        return $this->model->all()->orderBy($orderColumn,$order);
    }

    /**
     * Get all the deleted recordings ordered according to the given column.
     *
     * @param  string  $orderColumn
     * @param  string  $order (ex.: 'asc', 'desc')
     * @param  bool  $only, select only the deleted ones if true, select all existing records if set to false. Default to true.
     * @return array
     */
    public function getAllOrderedTrashed($orderColumn, $order, $only=true)
    {
        return $only ? 
            $this->getAllOrdered($orderColumn, $order)->onlyTrashed() :
            $this->getAllOrdered($orderColumn, $order)->withTrashed();
    }

    /**
     * Get the recordings matching the given WHERE clause
     *
     * @param  string  $column
     * @param  mixed  $value
     * @param  int  $limit, defaults to 100
     * @return array
     */
    public function getWhere($column, $value, $limit=100)
    {
        $search = '%'.strtolower($value).'%';
        return $this->model->whereRaw('LOWER('.$column.') LIKE ?', array($search))->take($limit)->get();
    }

    /**
     * Get the deleted recordings matching the given WHERE clause
     *
     * @param  string  $column
     * @param  mixed  $value
     * @param  int  $limit, defaults to 100
     * @param  bool  $only, select only the deleted ones if true, select all existing records if set to false. Default to true.
     * @return array
     */
    public function getWhereTrashed($column, $value, $limit = 100, $only = true)
    {
        $search = '%'.strtolower($value).'%';
        $query = $this->model->whereRaw('LOWER('.$column.') LIKE ?', array($search))->take($limit);
        if($only)
            $query = $query->onlyTrashed();
        else
            $query = $query->withTrashed();

        return $query->get();
    }

    /**
     * Get a single recording by its id.
     *
     * @param  int  $id
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function getById($id)
    {
        return $this->model->findOrFail($id);
    }

    /**
     * Get a single deleted recording by its id.
     *
     * @param  int  $id
     * @param  bool  $only, select only the deleted ones if true, select all existing records if set to false. Default to true.
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function getByIdTrashed($id, $only = true)
    {
        return $only ?
            $this->model->onlyTrashed()->findOrFail($id) :
            $this->model->withTrashed()->findOrFail($id);
    }

    /**
     * Get a paginate of the recordings.
     *
     * @param  int  $n the amount of recordings per page
     * @return array
     */
    public function getPaginate($n)
    {
        return $this->model->paginate($n);
    }

    /**
     * Get a paginate of the deleted recordings.
     *
     * @param  int  $n the amount of recordings per page
     * @param  bool  $only, select only the deleted ones if true, select all existing records if set to false. Default to true.
     * @return array
     */
    public function getPaginateTrashed($n, $only = true)
    {
        $paginate = $this->model->getPaginate($n);
        return $only ?
            $paginate->onlyTrashed() :
            $paginate->withTrashed();
    }

    /**
     * Get a paginate of the recordings with only selected columns.
     *
     * @param  int  $n the amount of recordings per page
     * @param  array  $columns the columns to select with optional alias
     * @return array
     */
    public function getPaginateSelect($n, array $columns)
    {
        return $this->model->select($columns)->paginate($n);
    }

    /**
     * Get a paginate of the deleted recordings with only selected columns.
     *
     * @param  int  $n the amount of recordings per page
     * @param  array  $columns the columns to select with optional alias
     * @param  bool  $only, select only the deleted ones if true, select all existing records if set to false. Default to true.
     * @return array
     */
    public function getPaginateSelectTrashed($n, array $columns, $only = true)
    {
        $paginate = $this->model->getPaginateSelect($n, $columns);
        return $only ?
            $paginate->onlyTrashed() :
            $paginate->withTrashed();
    }

    /**
     * Get a paginate of the recordings ordered according to the given column.
     *
     * @param  int  $n the amount of recordings per page
     * @param  string  $orderColumn
     * @param  string  $order (ex.: 'asc', 'desc')
     * @return array
     */
    public function getPaginateOrdered($n, $orderColumn, $order)
    {
        return $this->model->orderBy($orderColumn,$order)->paginate($n);
    }

    /**
     * Get a paginate of the deleted recordings ordered according to the given column.
     *
     * @param  int  $n the amount of recordings per page
     * @param  string  $orderColumn
     * @param  string  $order (ex.: 'asc', 'desc')
     * @param  bool  $only, select only the deleted ones if true, select all existing records if set to false. Default to true.
     * @return array
     */
    public function getPaginateOrderedTrashed($n, $orderColumn, $order, $only = true)
    {
        $paginate = $this->model->getPaginateOrdered($n, $orderColumn, $order);
        return $only ?
            $paginate->onlyTrashed() :
            $paginate->withTrashed();
    }

    /**
     * Get if a record exists with the given id
     *
     * @param  int  $id
     * @return boolean
     */
    public function exists($id)
    {
        return $this->model->where($this->model->getKeyName(), $id)->exists();
    }

    /**
     * Get if a deleted record exists with the given id
     *
     * @param  int  $id
     * @param  bool  $only, select only the deleted ones if true, select all existing records if set to false. Default to true.
     * @return boolean
     */
    public function existsTrashed($id, $only = true)
    {
        $query = $this->model->where($this->model->getKeyName(), $id);
        if($only)
            $query = $query->onlyTrashed();
        else
            $query = $query->withTrashed();

        return $query->exists();
    }

    /**
     * Resource relative behavior for saving a record.
     *
     * @param  Model  $model
     * @param  array  $inputs
     * @return int  the id of the saved resource
     */
    protected abstract function save(Model $model, Array $inputs);

    /**
     * Create and save a new record from the inputs.
     *
     * @param array $inputs
     * @return \Illuminate\Database\Eloquent\Model resource, the generated record
     */
    public function store(Array $inputs)
    {
        $resource = new $this->model;

        $this->save($resource, $inputs);

        return $resource;
    }

    /**
     * Update a record
     *
     * @param  int  $id, the id of the record to update
     * @param  array  $inputs
     * @return void
     */
    public function update($id, Array $inputs)
    {
        $this->getById($id)->update($inputs);
    }

    /**
     * Delete a record
     *
     * @param  int  $id, the id of the record to delete
     * @return void
     */
    public function destroy($id, $force = false)
    {
        $record = $this->getById($id);
        $force ? $record->forceDelete() : $record->delete();
    }

    /**
     * Restore a deleted record
     *
     * @param  int  $id, the id of the record to restore
     * @return void
     */
    public function restore($id)
    {
        $this->getByIdTrashed($id)->restore();
    }

}
