<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'address'
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
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function contacts()
    {
        return $this->belongsToMany('App\Models\User', 'contact_user', 'contact_id', 'user_id')->withTimestamps()->withPivot('notes');
    }

    /**
     * Get the files this user has sent.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function sentFiles()
    {
        return $this->hasMany('App\Models\File', 'sender_id');
    }

    /**
     * Get the files this user has received.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function receivedFiles()
    {
        return $this->hasMany('App\Models\File', 'recipient_id');
    }

    /**
     * Hash and set the user's password.
     *
     * @param  string  $password
     * @return void
     */
    public function setPasswordAttribute(string $password)
    {
        $this->attributes['password'] = Hash::make($password);
    }
}
