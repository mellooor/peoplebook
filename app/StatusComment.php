<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StatusComment extends Model
{
    public $timestamps = false;

    public function status() {
        return $this->belongsTo('App\Status', 'status_id');
    }

    public function likes() {
        return $this->hasMany('App\StatusCommentLike', 'comment_id');
    }

    public function commenter() {
        return $this->belongsTo('App\User', 'author_id');
    }
}
