<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Friendship;
use App\User;

class FriendshipsController extends Controller
{
    public function createFriendRequest($targetUserID) {
        $currentUserID = \Auth::user()->id;

        $friendRequest = new Friendship();
        $friendRequest->user1_id = $currentUserID;
        $friendRequest->user2_id = $targetUserID;
        $friendRequest->is_request = 1;
        $friendRequest->save();

        // A reciprocal request is also created (chosen to make it easier to lookup friendship/friend requests).
        $friendRequestReciprocal = new Friendship();
        $friendRequestReciprocal->user1_id = $targetUserID;
        $friendRequestReciprocal->user2_id = $currentUserID;
        $friendRequestReciprocal->is_request = 1;
        $friendRequestReciprocal->save();
    }

    public function cancelFriendRequest($targetUserID) {
        $currentUserID = \Auth::user()->id;

        $friendRequests = Friendship::where(function($query) use ($targetUserID, $currentUserID) {
            $query->where('user1_id', '=', $currentUserID)
                    ->where('user2_id', '=', $targetUserID);
        })->orWhere(function($query) use ($targetUserID, $currentUserID) {
            $query->where('user1_id', '=', $targetUserID)
                    ->where('user2_id', '=', $currentUserID);
        })->get();

        if ($friendRequests) {
            foreach ($friendRequests as $friendRequest) {
                $friendRequest->delete();
            }
        }
    }

    public function FriendRequestsIndex() {
        $currentUserID = \Auth::user()->id;

        $friendRequests = User::find($currentUserID)->users;

//        foreach ($friendRequests as $friendRequest) {
//            $userID = $friendRequest->user2_id;
//
//            $data[] = [
//                'user' => User::find($userID)->
//            ];
//        }

        return view('friend-requests')->with('requests', $friendRequests);
    }

    public function acceptFriendRequest($userID) {
        $currentUserID = \Auth::user()->id;

        $friendRequests = User::find($currentUserID)->users;
    }
}
