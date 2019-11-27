<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Job extends Model
{
    public $timestamps = false;

    public function employer() {
        return $this->belongsTo('App\Company', 'employer_id');
    }
}
