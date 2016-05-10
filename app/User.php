<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Config;

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
        'email', 'name', 'firstName', 'lastName', 'gender', 'birthday', 'role_id', 'password', 'avatar', 'activate_token'
    ];

    protected $hidden = [
        'password', 'remember_token'
    ];


    public function isAdmin() {
        return $this->role->id == Config::get('constants.ROLE_ADMIN');
    }

    public function isAuthor() {
        return $this->role->id == Config::get('constants.ROLE_AUTHOR');
    }

    public function isUser() {
        return $this->role->id == Config::get('constants.ROLE_USER');
    }


    public function role() {
        return $this->belongsTo('App\Role');
    }

    public function news() {
        return $this->hasMany('App\News');
    }

    public function comment() {
        return $this->hasMany('App\Comment');
    }

    public function like() {
        return $this->hasMany('App\Like');
    }
}
