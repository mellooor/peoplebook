<?php

namespace App\Http\Controllers;

use App\Activity;
use App\User;
use App\Status;
use Illuminate\Support\Facades\Auth;

class pagesController extends Controller
{
    public function notifications() {
        return view('notifications');
    }

    public function user($id = null) {
        if ($user = User::getFromRouteParameter($id)) {
            $data = ['user' => $user];

            $data['newsFeedItems'] = Activity::where(function($q) use ($user) {
                /*
                 * Get all activities for a user where the activity is one for a status being created, a photo
                 * being uploaded, a profile picture being changed or a new friendship.
                 */
                $q->where('user1_id', '=', $user->id)
                    ->orWhere('user2_id', '=', $user->id);
            })->where(function($q) {
                    $q->where('created_status_id', '!=', null)
                        ->orWhere('uploaded_photo_id', '!=', null)
                        ->orWhere('updated_profile_picture_photo_id', '!=', null)
                        ->orWhere('new_friendship_id', '!=', null);
                })->orderBy('created_at', 'DESC')->paginate(5);

            return view('user')->with('data', $data);
        } else {
            return redirect()->route('home');
        }
    }

    public function friendRequests() {
        return view('friend-requests');
    }

    public function settings() {
        return view('settings');
    }

    public function friends($id = null) {
        if ($user = User::getFromRouteParameter($id)) {
            return view('friends')->with('user', $user);
        } else {
            return redirect()->route('home');
        }
    }

    public function search($term) {
        $currentUser = User::find(Auth::user()->id);

        $userMatches = User::query()
            ->where(function($q) use ($term, $currentUser) {
                $q->where('first_name', 'LIKE', "%{$term}%")
                    ->where('id', '!=', $currentUser->id); // Exclude the current user.
            })->orWhere(function($q) use ($term, $currentUser) {
                $q->where('last_name', 'LIKE', "%{$term}%")
                    ->where('id', '!=', $currentUser->id); // Exclude the current user.
            })->get();

        $statusMatches = Status::query()
            ->where('content', 'LIKE', "%{$term}%")
            ->where('author_id', '!=', $currentUser->id) // Exclude any statuses that were created by the current user
            ->get();

//        $pageMatches = ...


        // Filter the search collections that are affected by privacy settings.
        $userMatches = $currentUser->filterUsersFromCollection($userMatches);
        $statusMatches = $currentUser->filterStatusesFromCollection($statusMatches);

        $matchesArray = [
            'term' => $term,
            'users' => $userMatches,
            'statuses' => $statusMatches
        ];

        return view('search')->with('results', $matchesArray);
    }

    public function userMoreInfo($id) {
        return view('more-info')->with('userID', $id);
    }

    public function status($id) {
        return view('status')->with('statusID', $id);
    }

    public function newsFeedIndex() {
        if (\Auth::check()) {
            $currentUserID = Auth::user()->id;
            $currentUser = User::find($currentUserID);
            $data = [];

            $data['newsFeedItems'] = Activity::where(function($q) use ($currentUser) {
                $currentUserFriendIDs = $currentUser->getAllFriendIDs();

                /*
                 * Get all activities where at least one of the users is a friend of the current user, neither of
                 * the users are the current user and where the activity is one for a status being created, a photo
                 * being uploaded, a profile picture being changed or a new friendship.
                 */
                $q->whereIn('user1_id', $currentUserFriendIDs)
                    ->orWhereIn('user2_id', $currentUserFriendIDs);
            })->where('user1_id', '!=', $currentUserID)
                ->where(function($q) use ($currentUserID) {
                $q->where('user2_id', '!=', $currentUserID)
                    ->orWhere('user2_id', '=', null);
            })->where(function($q) {
                $q->where('created_status_id', '!=', null)
                    ->orWhere('uploaded_photo_id', '!=', null)
                    ->orWhere('updated_profile_picture_photo_id', '!=', null)
                    ->orWhere('new_friendship_id', '!=', null);
            })->orderBy('created_at', 'DESC')->paginate(10);

            $data['user'] = $currentUser;


            return view('home')->with('data', $data);
        } else {
            return view('welcome');
        }
    }
}
