<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    /**
     * Table name in database
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'email', 'name', 'firstName', 'lastName', 'gender', 'birthday', 'role_id', 'password', 'activate_token'
    ];

    protected $hidden = [
        'remember_token'
    ];

    public function role() {
        return $this->belongsTo('App\Role');
    }
}
