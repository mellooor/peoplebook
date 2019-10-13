<?php

namespace App;

use App\Traits\FormatDateTrait;
use Illuminate\Database\Eloquent\Model;
use App\Library\DateTime as PeopleBookDateTime;

class Friendship extends Model
{
    public $timestamps = false;
    protected $dates = ['created_at'];

    public function user1() {
        return $this->belongsTo('App\User', 'user1_id');
    }

    public function user2() {
        return $this->belongsTo('App\User', 'user2_id');
    }

    public function activity() {
        return $this->hasOne('App\Activity', 'new_friendship_id');
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

    /*
     * Returns the ID of the user of a friendship that involves the current user which doesn't belong to the user themselves.
     */
    public function nonCurrentUser() {
        $currentUserID = \Auth::user()->id;

        // Only return a valid ID if the current user is one of the users in the friendship.
        if ($this->user1_id === $currentUserID || $this->user2_id === $currentUserID) {
            if ($this->user1_id === $currentUserID) {
                return $this->user2;
            } elseif ($this->user2_id === $currentUserID) {
                return $this->user1;
            } else {
                return 0;
            }
        } else {
            return 0;
        }
    }

    /*
     * Returns the duration up to now since the friendship was created in a format set in the Peoplebook
     * DateTime class.
     */
    public function createdAtDuration() {
        return PeopleBookDateTime::formatDuration($this->created_at);
    }
}
