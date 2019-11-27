<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Relationship extends Model
{
    public $timestamps = false;

    public function relationshipType() {
        return $this->belongsTo('App\RelationshipType', 'relationship_type_id');
    }

    public function relationshipRequestAcceptedActivity() {
        return $this->hasOne('App\Activity', 'new_relationship_id');
    }

    public function user1() {
        return $this->belongsTo('App\User', 'user1_id');
    }

    public function user2() {
        return $this->belongsTo('App\User', 'user2_id');
    }

    public function nonCurrentUser() {
        return $this->otherUser(Auth::user()->id);
    }

    public function otherUser(int $id) {
        if ($this->user1_id === $id) {
            return $this->belongsTo('App\User', 'user2_id')->first(); // first() method call required, as an instance of belongsTo is returned due to the parentheses being required on teh call to otherUser in order to pass the parameter.
        } elseif ($this->user2_id === $id) {
            return $this->belongsTo('App\User', 'user1_id')->first(); // first() method call required, as an instance of belongsTo is returned due to the parentheses being required on teh call to otherUser in order to pass the parameter.
        } else {
            return null;
        }
    }

    /*
     * Checks to see whether a relationship exists.
     *
     * @param integer $user1ID the ID of the first user.
     * @param integer $user2ID the ID of the second user (optional).
     * @return boolean
     */
    public static function exists($user1ID, $user2ID = 0) {
        if ($user2ID) {
            $friendship = self::where(function($query) use ($user1ID, $user2ID) {
                $query->where('user1_id', '=', $user1ID)
                    ->where('user2_id', '=', $user2ID);
            })->orWhere(function($query) use ($user1ID, $user2ID) {
                $query->where('user1_id', '=', $user2ID)
                    ->where('user2_id', '=', $user1ID);
            })->get();
        } else {
            $friendship = self::where('user1_id', '=', $user1ID)->get();
        }

        return (count($friendship) > 0);
    }
}
