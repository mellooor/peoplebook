<?php

namespace App;

use App\Traits\FormatDateTrait;
use Illuminate\Database\Eloquent\Model;
use App\Library\DateTime as PeopleBookDateTime;

class Status extends Model
{
    public $timestamps = false;
    protected $dates = ['created_at', 'updated_at'];

    public function author() {
        return $this->belongsTo('App\User', 'author_id');
    }

    public function likes() {
        return $this->hasMany('App\StatusLike', 'status_id');
    }

    public function comments() {
        return $this->hasMany('App\StatusComment', 'status_id');
    }

    public function photos() {
        return $this->hasMany('App\StatusPhoto', 'status_id');
    }

    public function activity() {
        return $this->hasOne('App\Activity', 'created_status_id', 'id');
    }

    public function privacy() {
        return $this->belongsTo('App\PrivacyType', 'privacy_type_id');
    }

    /*
     * Verifies that a variable is a Status model.
     *
     * @param $sample - The variable that is to be verified.
     *
     * @return boolean - True/False dependent on whether the argument is a Status model.
     */
    public static function isStatusModel($sample) {
        if (is_object($sample)) {
            return (get_class($sample) === "App\Status");
        } else {
            return false;
        }
    }

    /*
     * Returns the duration up to now since the status was created in a format set in the Peoplebook
     * DateTime class.
     */
    public function createdAtDuration() {
        return PeopleBookDateTime::formatDuration($this->created_at);
    }

    /*
     * Returns the duration up to now since the status was edited in a format set in the Peoplebook
     * DateTime class.
     */
    public function updatedAtDuration() {
        return PeopleBookDateTime::formatDuration($this->updated_at);
    }
}
