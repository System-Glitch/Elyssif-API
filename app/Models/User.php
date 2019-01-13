<?php

namespace App\Models;

use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * Get the contacts for this user.
     *
     * @return Illuminate/Database/Eloquent/Relations/BelongsToMany
     */
    public function contacts()
    {
        return $this->belongsToMany('App\Models\User', 'contact_user', 'contact_id', 'user_id')->withPivot('notes', 'created_at', 'updated_at');
    }

    /**
     * Get the files this user has sent.
     *
     * @return Illuminate/Database/Eloquent/Relations/HasMany
     */
    public function sentFiles()
    {
        return $this->hasMany('App\Models\File', 'id_sender');
    }

    /**
     * Get the files this user has received.
     *
     * @return Illuminate/Database/Eloquent/Relations/HasMany
     */
    public function receivedFiles()
    {
        return $this->hasMany('App\Models\File', 'id_recipient');
    }
}
