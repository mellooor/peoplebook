<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StatusPhoto extends Model
{
    public $timestamps = false;

    public function status() {
        return $this->belongsTo('App\Status', 'status_id');
    }

    public function information() {
        return $this->belongsTo('App\Photo', 'photo_id');
    }
}
