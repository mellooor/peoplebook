<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Friendship extends Model
{
    public $timestamps = false;

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
}
