<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FriendRequest extends Model
{
    public function user1() {
        return $this->belongsTo('App\User', 'user1_id');
    }

    public function user2() {
        return $this->belongsTo('App\User', 'user2_id');
    }
}
