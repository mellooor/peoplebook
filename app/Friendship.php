<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Friendship extends Model
{
    /*
     *  Return the non current-user from a friendship/friendship request.
     */
    public function user() {
        return $this->belongsTo('App\User', 'user1_id');
    }
}
