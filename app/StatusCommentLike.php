<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StatusCommentLike extends Model
{
    public $timestamps = false;

    public function comment() {
        return $this->belongsTo('App\StatusComment', 'comment_id');
    }

    public function liker() {
        return $this->belongsTo('App\User', 'user_id');
    }
}
