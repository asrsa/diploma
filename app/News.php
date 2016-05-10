<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    protected $table = 'news';

    public function user() {
        return $this->belongsTo('App\User');
    }

    public function subcategory() {
        return $this->belongsTo('App\Subcategory');
    }

    public function comment() {
        return $this->hasMany('App\Comment');
    }
}
