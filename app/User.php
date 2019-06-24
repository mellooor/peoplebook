<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'date_of_birth',
        'profile_picture',
        'relationship_type_id',
        'email',
        'date_created',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /*
     * Get the friends/friendRequests for a user.
     */
    public function friendRequestUsers1() {
        return $this->hasMany('App\FriendRequest', 'user1_id');
    }

    public function friendRequestUsers2() {
        return $this->hasMany('App\FriendRequest', 'user2_id');
    }

    public function friendshipUsers1() {
        return $this->hasMany('App\Friendship', 'user1_id');
    }

    public function friendshipUsers2() {
        return $this->hasMany('App\Friendship', 'user2_id');
    }

    public function statuses() {
        return $this->hasMany('App\Status', 'author_id');
    }

    /*
     * Returns an array of all of the userIDs of the friends of a user's friends that aren't the user themselves
     * nor a user that the current user is already friends with.
     *
     * This method begins by fetching all of the user IDs of the current user's friends, before looping through each
     * ID to return all of the user IDs of the friend's friends. These IDs are added to an existing array during each
     * iteration and this array is then filtered to remove any duplicate IDs, as well as removing user IDs that match
     * either the current user's or any of their friends'.
     *
     * @return Array - an array of all of the user IDs for the friends of friends.
     */
    public function friendsOfFriendsIDs() {
        $friendsOfFriendsIDsArray = [];

        // Get all friends1 and friends 2 of the user and merge into one friends collection.
        // Keep as collection due to requiring the User models for extracting the user IDs.
        // Make the following line more readable by adding a separate method to the class.
        $friendsIDsArray = $this->getAllFriendIDs();

        // For each friend in the friends collection, retrieve all friends and add their ids to an array.
        foreach ($friendsIDsArray as $friendID) {
            $friendsOfFriendCollection = collect();

            $friend = self::find($friendID);

            // the collections currently hold friendships HasMany objects, rather than users - the id's that are being
            // returned are for the friendships. The friendsOfFriendCollection/Array needs to add
            // $friendshipUsers1->pluck('user2_id'). Same with friendshipUsers2.
            foreach ($friend->friendshipUsers1 as $friendOfFriend1) {
                $friendsOfFriendsIDsArray[] = $friendOfFriend1->user2_id;
            }

            foreach ($friend->friendshipUsers2 as $friendOfFriend2) {
                $friendsOfFriendsIDsArray[] = $friendOfFriend2->user1_id;
            }
        }

        // Convert the friends of friends collection to an array and remove all duplicate IDs from the array.
        $friendsOfFriendsIDsArray = array_unique($friendsOfFriendsIDsArray);

        // Remove the current user ID from the array if it's found within it.
        // The conditional needs to explicitly specify false, as array_search can return a falsy value if the
        // array key returned is 0.
        if (($currentUserIDKey = array_search($this->id, $friendsOfFriendsIDsArray)) !== false) {
            unset($friendsOfFriendsIDsArray[$currentUserIDKey]);

            // Unset converts the array into an associative array, which we don't need.
            // Therefore, we need to convert it back into a simple array.
            $friendsOfFriendsIDsArray = array_values($friendsOfFriendsIDsArray);
        }

        // Remove all IDs that are the ID of any of the current user's friends.
        $friendsOfFriendsIDsArray = array_diff($friendsOfFriendsIDsArray, $friendsIDsArray);

        return $friendsOfFriendsIDsArray;
    }

    /*
     * Returns an array of all of the IDs of the users that the current user is friends with.
     *
     * Takes all of the users returned by friendshipUsers1 and friendshipUsers2 and plucks the user ID values that
     * aren't the current user ID (found in the 'user2_id' and 'users1_id' columns respectively).
     *
     * @return array - An array of the user IDs
     */
    public function getAllFriendIDs() {
        return $this->friendshipUsers1->pluck('user2_id')->merge($this->friendshipUsers2->pluck('user1_id'))->toArray();
    }
}
