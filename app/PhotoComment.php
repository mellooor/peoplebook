<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Library\DateTime as PeopleBookDateTime;

class PhotoComment extends Model
{
    public $timestamps = false;
    protected $dates = ['created_at', 'updated_at'];

    public function author() {
        return $this->belongsTo('App\User', 'author_id');
    }

    public function rawPhoto() {
        return $this->belongsTo('App\Photo', 'raw_photo_id');
    }

    public function likes() {
        return $this->hasMany('App\PhotoCommentLike', 'comment_id');
    }

    public function activity() {
        return $this->hasOne('App\Activity', 'photo_comment_id');
    }

    /*
     * Returns the duration up to now since the photo comment was created in a format set in the Peoplebook
     * DateTime class.
     */
    public function createdAtDuration() {
        return PeopleBookDateTime::formatDuration($this->created_at);
    }

    /*
     * Returns the duration up to now since the photo comment was edited in a format set in the Peoplebook
     * DateTime class.
     */
    public function updatedAtDuration() {
        return PeopleBookDateTime::formatDuration($this->updated_at);
    }
}
