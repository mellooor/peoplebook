<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class RelationshipRequest extends Model
{
    public $timestamps = false;

    public function activity() {
        return $this->hasOne('App\Activity', 'relationship_request_id');
    }

    public function nonCurrentUser() {
        return $this->otherUser(Auth::user()->id);
    }

    public function otherUser($userID) {
        if ($this->user1_id === $userID) {
            return $this->belongsTo('App\User', 'user2_id');
        } elseif ($this->user2_id === $userID) {
            return $this->belongsTo('App\User', 'user1_id');
        } else {
            return null;
        }
    }

    /*
     * Validates whether 2 supplied user IDs match against those that belong to the relationship request.
     *
     * @param integer $user1ID The ID of the first user.
     * @param integer $user2ID The ID of the second user.
     * @return boolean
     */
    public function userIDsMatch($user1ID, $user2ID) {
        return ($this->user1_id === $user1ID && $this->user2_id === $user2ID) || ($this->user1_id === $user2ID && $this->user2_id === $user1ID);
    }
}
