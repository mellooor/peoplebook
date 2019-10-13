<?php

namespace App;

use App\Traits\FormatDateTrait;
use Illuminate\Database\Eloquent\Model;
use App\Library\DateTime as PeopleBookDateTime;

class StatusComment extends Model
{
    public $timestamps = false;
    protected $dates = ['created_at', 'updated_at'];

    public function status() {
        return $this->belongsTo('App\Status', 'status_id');
    }

    public function likes() {
        return $this->hasMany('App\StatusCommentLike', 'comment_id');
    }

    public function commenter() {
        return $this->belongsTo('App\User', 'author_id');
    }

    public function activity() {
        return $this->hasOne('App\Activity', 'status_comment_id');
    }

    /*
     * Returns the duration up to now since the status comment was created in a format set in the Peoplebook
     * DateTime class.
     */
    public function createdAtDuration() {
        return PeopleBookDateTime::formatDuration($this->created_at);
    }

    /*
     * Returns the duration up to now since the status comment was edited in a format set in the Peoplebook
     * DateTime class.
     */
    public function updatedAtDuration() {
        return PeopleBookDateTime::formatDuration($this->updated_at);
    }
}
