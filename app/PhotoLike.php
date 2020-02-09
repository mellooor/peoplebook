<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PhotoLike extends Model
{
    public $timestamps = false;

    public function rawPhoto() {
        return $this->belongsTo('App\Photo', 'raw_photo_id');
    }

    public function liker() {
        return $this->belongsTo('App\User', 'user_id');
    }

    public function activity() {
        return $this->hasOne('App\Activity', 'photo_like_id');
    }
}
