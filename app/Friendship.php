<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Friendship extends Model
{
    public $timestamps = false;

    public function user1() {
        return $this->belongsTo('App\User', 'user1_id');
    }

    public function user2() {
        return $this->belongsTo('App\User', 'user2_id');
    }

    /*
     * Checks to see whether a friendship between 2 users exists.
     *
     * @param integer $user1ID the ID of the first user.
     * @param integer $user2ID the ID of the second user.
     * @return boolean
     */
    public static function exists($user1ID, $user2ID) {
        $friendship = self::where(function($query) use ($user1ID, $user2ID) {
           $query->where('user1_id', '=', $user1ID)
                    ->where('user2_id', '=', $user2ID);
        })->orWhere(function($query) use ($user1ID, $user2ID) {
          $query->where('user1_id', '=', $user2ID)
                    ->where('user2_id', '=', $user1ID);
        })->get();

        return count($friendship);
    }

    public function userIDsMatch($user1ID, $user2ID) {
        return (($this->user1_id === $user1ID && $this->user2_id === $user2ID) || ($this->user1_id === $user2ID && $this->user2_id === $user1ID));
    }
}
