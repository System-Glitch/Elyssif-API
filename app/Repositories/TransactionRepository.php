<?php

namespace App\Repositories;

use App\Models\Transaction;
use Illuminate\Database\Eloquent\Model;

class TransactionRepository extends ResourceRepository
{

    /**
     * Create a new repository instance.
     *
     * @param  \App\Models\Transaction  $model
     * @return void
     */
    public function __construct(Transaction $model)
    {
        $this->model = $model;
    }

    /**
     * Get transactions by txid.
     * @param string  $txid the txid of the transaction 
     * @param array|mixed  $columns the columns to select with optional alias, defaults to '*'
     * @return array
     */
    public function getByTxId(string $txid, array $columns = ['*'])
    {
        return $this->model->where('txid', $txid)->select($columns)->get();
    }
    
    /**
     * Get if a transaction exists with the given txid.
     * @param string  $txid the txid of the transaction
     * @return bool
     */
    public function existsByTxId(string $txid)
    {
        return $this->model->where('txid', $txid)->exists();
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
        $model->txid = $inputs['txid'];
        $model->file_id = $inputs['file_id'];
        if(isset($inputs['confirmed'])) $model->confirmed = $inputs['confirmed'];
        $model->amount = $inputs['amount'];

        $model->save();
        return $model->id;
    }

}
