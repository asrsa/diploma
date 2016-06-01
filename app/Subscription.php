<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    protected $table = 'subscriptions';
    public $timestamps = false;

    public function user() {
        return $this->belongsTo('App\User');
    }

    public function category() {
        return $this->belongsTo('App\Category');
    }
}
