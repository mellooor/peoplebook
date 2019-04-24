<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    public $timestamps = false;

    public function author() {
        return $this->belongsTo('App\User', 'author_id');
    }

    public function likes() {
        return $this->hasMany('App\StatusLike', 'status_id');
    }

    public function comments() {
        return $this->hasMany('App\StatusComment', 'status_id');
    }
}
