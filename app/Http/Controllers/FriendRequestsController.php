<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;

class FriendRequestsController extends Controller
{
    public function index() {
        $currentUserID = \Auth::user()->id;

        $friendRequests = User::find($currentUserID)->friendRequestUsers1;
        $friendRequests = $friendRequests->merge(User::find($currentUserID)->friendRequestUsers2);
        $requests = [];

        /*
         * For each friend request, store the user of the friend request that isn't the current user in the targetUsersArray Array.
         */
        foreach ($friendRequests as $friendRequest) {
            if ($friendRequest->user1_id === $currentUserID) {
                $requests[] = [
                    'id' => $friendRequest->id,
                    'user' => $friendRequest->user2
                ];
            } else if ($friendRequest->user2_id === $currentUserID) {
                $requests[] = [
                    'id' => $friendRequest->id,
                    'user' => $friendRequest->user1
                ];
            }
        }

        return view('friend-requests')->with('requests', $requests);
    }

    public function destroy($targetUserID)
    {
        $currentUserID = \Auth::user()->id;

        $friendRequests = Friendship::where(function ($query) use ($targetUserID, $currentUserID) {
            $query->where('user1_id', '=', $currentUserID)
                ->where('user2_id', '=', $targetUserID);
        })->orWhere(function ($query) use ($targetUserID, $currentUserID) {
            $query->where('user1_id', '=', $targetUserID)
                ->where('user2_id', '=', $currentUserID);
        })->get();

        if ($friendRequests) {
            foreach ($friendRequests as $friendRequest) {
                $friendRequest->delete();
            }
        }
    }

    public function acceptFriendRequest($userID) {
        $currentUserID = \Auth::user()->id;

        $friendRequests = User::find($currentUserID)->users;
    }

    public function store($targetUserID) {
        $currentUserID = \Auth::user()->id;

        $friendRequest = new FriendRequest();
        $friendRequest->user1_id = $currentUserID;
        $friendRequest->user2_id = $targetUserID;
        $friendRequest->save();
    }

    public static function count() {
        $currentUserID = \Auth::user()->id;

        $friendRequests = User::find($currentUserID)->friendRequestUsers1;
        $friendRequests = $friendRequests->merge(User::find($currentUserID)->friendRequestUsers2);
        $count = 0;

        /*
         * For each friend request, store the user of the friend request that isn't the current user in the targetUsersArray Array.
         */
        foreach ($friendRequests as $friendRequest) {
            if ($friendRequest->user1_id === $currentUserID) {
                $count++;
            } else if ($friendRequest->user2_id === $currentUserID) {
                $count++;
            }
        }

        return $count;
    }
}
