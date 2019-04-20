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

    /*
     * Validates whether 2 supplied user IDs match against those that belong to the friend request.
     *
     * @param integer $user1ID The ID of the first user.
     * @param integer $user2ID The ID of the second user.
     * @return boolean
     */
    public function userIDsMatch($user1ID, $user2ID) {
        return ($this->user1_id === $user1ID && $this->user2_id === $user2ID) || ($this->user1_id === $user2ID && $this->user2_id === $user1ID);
    }
}
