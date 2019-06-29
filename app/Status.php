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
}
