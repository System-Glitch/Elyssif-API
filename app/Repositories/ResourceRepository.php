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
    public function getAllTrashed(bool $only = true)
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
    public function getAllOrdered(string $orderColumn, string $order)
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
    public function getAllOrderedTrashed(string $orderColumn, string $order, bool $only=true)
    {
        return $only ? 
            $this->getAllOrdered($orderColumn, $order)->onlyTrashed() :
            $this->getAllOrdered($orderColumn, $order)->withTrashed();
    }

    /**
     * Get the recordings matching the given WHERE clause
     *
     * @param  string  $column
     * @param  string $operator
     * @param  mixed  $value
     * @param  int  $limit, defaults to 100
     * @return array
     */
    public function getWhere(string $column, string $operator, $value, int $limit=100)
    {
        $search = $operator == 'LIKE' ? '%'.$this->escape_like($value).'%' : $value;
        return $this->model->where($column, $operator, $search)->take($limit)->get();
    }

    /**
     * Get the deleted recordings matching the given WHERE clause
     *
     * @param  string  $column
     * @param  string $operator
     * @param  mixed  $value
     * @param  int  $limit, defaults to 100
     * @param  bool  $only, select only the deleted ones if true, select all existing records if set to false. Default to true.
     * @return array
     */
    public function getWhereTrashed(string $column, string $operator, $value, int $limit = 100, bool $only = true)
    {
        $search = $operator == 'LIKE' ? '%'.$this->escape_like($value).'%' : $value;
        $query = $this->model->where($column, $operator, $search)->take($limit)->get();
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
    public function getById(int $id)
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
    public function getByIdTrashed(int $id, bool $only = true)
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
    public function getPaginate(int $n)
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
    public function getPaginateTrashed(int $n, bool $only = true)
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
    public function getPaginateSelect(int $n, array $columns)
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
    public function getPaginateSelectTrashed(int $n, array $columns, bool $only = true)
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
    public function getPaginateOrdered(int $n, string $orderColumn, string $order)
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
    public function getPaginateOrderedTrashed(int $n, string $orderColumn, string $order, bool $only = true)
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
     * @return bool
     */
    public function exists(int $id)
    {
        return $this->model->where($this->model->getKeyName(), $id)->exists();
    }

    /**
     * Get if a deleted record exists with the given id
     *
     * @param  int  $id
     * @param  bool  $only, select only the deleted ones if true, select all existing records if set to false. Default to true.
     * @return bool
     */
    public function existsTrashed(int $id, bool $only = true)
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
    public function update(int $id, Array $inputs)
    {
        $this->getById($id)->update($inputs);
    }

    /**
     * Delete a record
     *
     * @param  int  $id, the id of the record to delete
     * @return void
     */
    public function destroy(int $id, bool $force = false)
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

    /**
     * Escape special characters for a LIKE query.
     *
     * @param string $value
     * @param string $char
     *
     * @return string
     */
    private function escape_like(string $value, string $char = '\\')
    {
        return str_replace(
            [$char, '%', '_'],
            [$char.$char, $char.'%', $char.'_'],
            $value
        );
    }

}
