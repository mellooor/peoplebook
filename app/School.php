<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class School extends Model
{
    public $timestamps = false;

    public function schoolName() {
        return $this->belongsTo('App\SchoolName', 'school_id');
    }
}
