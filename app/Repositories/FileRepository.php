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
     * Get a file by its bitcoin address.
     * @param string  $address the bitcoin address for the file
     * @param array|mixed  $columns the columns to select with optional alias, defaults to '*'
     * @return \App\Models\File
     */
    public function getByAddress(string $address, array $columns = ['*'])
    {
        return $this->model->where('address', $address)->select($columns)->first();
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
        $files = $user->sentFiles()->whereNotNull('ciphered_at');
        if($search) {
            $files = $files->where('name', 'LIKE', '%'.$this->escape_like($search).'%');
        }

        return $files->select($columns)
                     ->with('recipient:id,name,email')
                     ->orderBy('ciphered_at', 'desc')
                     ->paginate($n);
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
        $files = $user->receivedFiles()->whereNotNull('ciphered_at');
        if($search) {
            $files = $files->where('name', 'LIKE', '%'.$this->escape_like($search).'%');
        }

        return $files->select($columns)
                     ->with('sender:id,name,email')
                     ->orderBy('ciphered_at', 'desc')
                     ->paginate($n);
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
                    ->with('sender:id,address')
                    ->first();
    }

    /**
     * Get a file matching the given ciphered hash
     * from the given user's received files.
     * @param \App\Models\User  $user
     * @param string  $cipheredHash
     * @param string  $hash
     * @return \App\Models\File
     */
    public function getFileForFetch(User $user, $cipheredHash)
    {
        return $user->receivedFiles()
                    ->where('hash_ciphered', $cipheredHash)
                    ->select('id', 'name', 'sender_id', 'recipient_id', 'price', 'address')
                    ->with('sender:id,name,email')
                    ->first();
    }

    /**
     * Find an unencrypted file (ciphered_at is null) by
     * its hash and name.
     * Only the id and the public key columns are selected.
     * @param int  senderId
     * @param string  $name
     * @param string  $hash
     */
    public function getUnencrypted(int $senderId, $name, $hash)
    {
        return $this->model->where('hash', $hash)
                           ->where('name', $name)
                           ->where('sender_id', $senderId)
                           ->whereNull('ciphered_at')
                           ->select('id', 'public_key')->first();
    }

    /**
     * Get the payment state of the given file.
     * (Amount awaiting confirmation and confirmed amount)
     * @param int|\App\Models\File $file
     * @return array
     */
    public function getPaymentState($file)
    {
        $state = [];
        $bitcoind = bitcoind();

        if(!($file instanceof File)) {
            $file = $this->getById($file, ['id','address']);
        }

        $total = $bitcoind->request('getReceivedByAddress', $file->address, 0)->result();
        $state['confirmed'] = $bitcoind->getReceivedByAddress($file->address, intval(env('MIN_CONFIRMATIONS')))->result();
        $state[ 'pending' ] = $total - $state['confirmed'];

        return $state;
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
        if(isset($inputs['address'])) $model->address = $inputs['address'];

        $model->save();
        return $model->id;
    }

}
