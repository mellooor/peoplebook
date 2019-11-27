<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Place extends Model
{
    public $timestamps = false;

    public function placeName() {
        return $this->belongsTo('App\PlaceName', 'place_name_id');
    }
}
