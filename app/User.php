<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;

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

    public function photos() {
        return $this->hasMany('App\Photo', 'uploader_id');
    }

    public function photoThumbnails() {
        return $this->hasMany('App\Photo', 'uploader_id')->where('type_id', 3);
    }

    /*
     * Returns a paginated version of all the photos of a certain user.
     *
     * @param int $number - the max number of photos to be returned on a page.
     *
     * @return Illuminate\Pagination\LengthAwarePaginator.
     */
    public function paginatedThumbnailPhotos($number) {
        return $this->photoThumbnails()->paginate($number);
    }

    public function activeProfilePicture() {
        return $this->hasMany('App\Photo', 'uploader_id')->where('type_id', 5)->first();
    }

    public function activeProfilePictureThumbnail() {
        return $this->hasOne('App\Photo', 'uploader_id')->where('type_id', 6);
    }

    public function notifications() {
        return $this->hasMany('App\Notification', 'user_id');
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
     * Returns a collection of all of the users that a user is friends with.
     *
     * @return Illuminate\Database\Eloquent\Collection - A collection of users.
     */
    public function getAllFriendships() {
        return $this->friendshipUsers1()->get()->merge($this->friendshipUsers2()->get());
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

    /*
     * Filters out users from a users collection based on their privacy setting and relation to the current user, before
     * returning another collection that contains the filtered users.
     *
     * If a user has their privacy setting set to 2 (friends of friends), then the user is filtered based on whether
     * the user's ID is found in the current user's friendsOfFriendsIDs() array. Similarly, if a user has their privacy
     * setting set to 3 (friends only), then the user is filtered based on whether the user's ID is found in the
     * current user's getAllFriendsIDs() array. Finally, if a user has their privacy setting set to 1 (public), then
     * the user isn't filtered.
     *
     * @param Collection $userCollection - A Laravel collection of users.
     *
     * @return Collection - A Laravel collection of the filtered users.
     */
    public function filterUsersFromCollection(Collection $usersCollection) {
        return $usersCollection->filter(function($user) {
            if (Self::isUserModel($user)) {
                if ($user->privacy_type_id === 1) {
                    return $user;
                } elseif ($user->privacy_type_id === 2) {
                    return in_array($user->id, $this->friendAndFriendOfFriendIDs());
                } elseif ($user->privacy_type_id === 3) {
                    return in_array($user->id, $this->getAllFriendIDs());
                }
            } else {
                // Return error.
            }
        });
    }

    /*
     * Filters out statuses from a statuses collection based on the privacy setting of the status and the relation of
     * the author to the current user, before returning another collection that contains the filtered statuses.
     *
     * If a status has its privacy setting set to 2 (friends of friends), then the status is filtered based on whether
     * the status' Author's ID is found in the current user's friendsOfFriendsIDs() array. Similarly, if a status has
     * its privacy setting set to 3 (friends only), then the status is filtered based on whether the status' Author's ID
     * is found in the current user's getAllFriendsIDs() array. Finally, if a status has its privacy setting set to 1
     * (public), then the status isn't filtered.
     *
     * @param Collection $statusesCollection - A Laravel collection of statuses.
     *
     * @return Collection - A Laravel collection of the filtered statuses.
     */
    public function filterStatusesFromCollection(Collection $statusesCollection) {
        return $statusesCollection->filter(function($status) {
            if (Status::isStatusModel($status)) {
                if ($status->privacy_type_id === 1) {
                    return $status;
                } elseif ($status->privacy_type_id === 2) {
                    return in_array($status->author_id, $this->friendAndFriendOfFriendIDs());
                } elseif ($status->privacy_type_id === 3) {
                    return in_array($status->author_id, $this->getAllFriendIDs());
                }
            } else {
                // Return error.
            }
        });
    }

    /*
     * Verifies that a variable is a User model.
     *
     * @param $object - The variable that is to be verified.
     *
     * @return boolean - True/False dependent on whether the argument is a User model.
     */
    public static function isUserModel($sample) {
        if (is_object($sample)) {
            return (get_class($sample) === "App\User");
        } else {
            return false;
        }
    }

    /*
     * Returns an array of all of the IDs of the users that the current user is friends with, as well as the
     * friends of friends for the current user.
     *
     * Takes all of the users returned by the friendsOfFriendsIDs and getAllFriendIDs methods and combines them into
     * a single array.
     *
     * @return array - An array of the user IDs
     */
    public function friendAndFriendOfFriendIDs() {
        return array_merge($this->friendsOfFriendsIDs(), $this->getAllFriendIDs());
    }

    /*
     * Returns a user from an ID value specified in a route parameter.
     *
     * If an invalid ID is passed, null is returned. If the ID itself is null, then the currently logged-in user
     * is returned.
     *
     * @param int $id - The user ID passed in the route.
     *
     * @return App\User - The user that corresponds to the ID passed in the route. Returns null when the ID is invalid.
     */
    public static function getFromRouteParameter($id) {
        if ($id) {
            return User::find($id);
        } else {
            return User::find(Auth::user()->id);
        }
    }
}
