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
        'public_key', 'private_key', 'price', 'sender_id', 'recipient_id'
    ];

    /**
     * Get the sender for this file.
     *
     * @return Illuminate/Database/Eloquent/Relations/BelongsTo
     */
    public function sender()
    {
        return $this->belongsTo('App\Models\User', 'id_sender');
    }

    /**
     * Get the recipient for this file.
     *
     * @return Illuminate/Database/Eloquent/Relations/BelongsTo
     */
    public function recipient()
    {
        return $this->belongsTo('App\Models\User', 'id_recipient');
    }

}
