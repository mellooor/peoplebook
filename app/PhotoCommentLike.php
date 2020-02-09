<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PhotoCommentLike extends Model
{
    public $timestamps = false;

    public function photoComment() {
        return $this->belongsTo('App\PhotoComment', 'comment_id');
    }

    public function liker() {
        return $this->belongsTo('App\User', 'user_id');
    }

    public function activity() {
        return $this->hasOne('App\Activity', 'photo_comment_like_id');
    }
}
