<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class File extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'ciphered_at', 'deciphered_at', 'hash', 'hash_ciphered', 
        'public_key', 'private_key', 'price', 'sender_id', 'recipient_id',
        'elyssif_addr'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'public_key', 'private_key', 'hash', 'hash_ciphered', 'elyssif_addr'
    ];

    /**
     * Get the sender for this file.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function sender()
    {
        return $this->belongsTo('App\Models\User', 'sender_id');
    }

    /**
     * Get the recipient for this file.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function recipient()
    {
        return $this->belongsTo('App\Models\User', 'recipient_id');
    }

    /**
     * Get the transactions related to this file.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function transactions()
    {
        return $this->hasMany('App\Models\Transaction', 'file_id');
    }

}
