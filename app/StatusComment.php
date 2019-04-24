<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StatusComment extends Model
{
    public $timestamps = false;

    public function status() {
        return $this->belongsTo('App\Status', 'status_id');
    }
}
