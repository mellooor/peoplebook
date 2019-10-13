<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StatusLike extends Model
{
    public $timestamps = false;

    public function status() {
        return $this->belongsTo('App\Status', 'status_id');
    }

    public function liker() {
        return $this->belongsTo('App\User', 'user_id');
    }
}
