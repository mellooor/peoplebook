<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\FriendRequest;
use App\Friendship;
use App\Activity;
use Illuminate\Support\Facades\DB;
use App\Notification;

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

    public function decline(Request $request)
    {
        $currentUserID = \Auth::user()->id;

        $request->validate([
            'friend-request-id' => 'integer|required',
            'target-user-id' => 'integer|required'
        ]);

        $targetFriendRequestID = intval($request->input('friend-request-id'));
        $targetUserID = intval($request->input('target-user-id'));

        /*
         * If the target friend request ID matches an existing id in the friend requests DB table...
         */
        if ($friendRequest = FriendRequest::find($targetFriendRequestID)) {
            /*
             * If the supplied user IDs match the user IDs in the friend request record from the DB table...
             */
            if ($friendRequest->userIDsMatch($currentUserID, $targetUserID)) {
                $friendRequest->delete();
                return redirect()->back()->with('declined', 'Friend Request Declined');
            } else {
                return redirect()->back();
            }
        } else {
            return redirect()->back();
        }
    }

    public function accept(Request $request) {
        $currentUserID = \Auth::user()->id;

        $request->validate([
           'friend-request-id' => 'integer|required',
           'target-user-id' => 'integer|required'
        ]);

        $targetFriendRequestID = intval($request->input('friend-request-id'));
        $targetUserID = intval($request->input('target-user-id'));

        /*
         * If the target friend request ID matches an existing id in the friend requests DB table...
         */
        if ($friendRequest = FriendRequest::find($targetFriendRequestID)) {
            /*
             * If the supplied user IDs match the user IDs in the friend request record from the DB table...
             */
            if ($friendRequest->userIDsMatch($currentUserID, $targetUserID)) {
                $friendshipExists = Friendship::exists($currentUserID, $targetUserID);

                /*
                 * If the friendship doesn't already exist, create a new friendship; otherwise, don't (preventing a duplicate record)
                 */
                if (!$friendshipExists) {
                    $friendship = new Friendship();
                    $friendship->user1_id = $currentUserID;
                    $friendship->user2_id = $targetUserID;
                    $friendship->created_at = date("Y-m-d");
                    if ($friendship->save()) {
                        $activity = new Activity();
                        $activity->user1_id = $currentUserID;
                        $activity->user2_id = $targetUserID;
                        $activity->new_friendship_id = $friendship->id;
                        $activity->created_at = date('Y-m-d H:i:s');

                        if (!$activity->save()) {
                            $friendship->delete();
                            return redirect()->back()->with('error', 'An error occurred. Please try again');
                        } else {
                            // Save Notification for the user that didn't accept the friend request (the one that isn't the current user).
                            if ($friendship->user1_id === $currentUserID) {
                                $otherFriendshipUserID = $friendship->user2_id;
                            } elseif ($friendship->user2_id === $currentUserID) {
                                $otherFriendshipUserID = $friendship->user1_id;
                            } else {
                                $otherFriendshipUserID = 0;
                            }

                            try {
                                $notification = new Notification();
                                $notification->user_id = $otherFriendshipUserID;
                                $notification->type_id = 3; // friend-request-accepted.
                                $notification->is_active = true;
                                $notification->activity_id = $activity->id;

                                if (!$notification->save()) {
                                    $friendship->delete();
                                    $activity->delete();
                                    return redirect()->back()->with('error', 'An error occurred. Please try again');
                                }
                            } catch (\Exception $e) {
                                $friendship->delete();
                                $activity->delete();
                                return redirect()->back()->with('error', 'An error occurred. Please try again');
                            }

                            $friendRequest->delete();
                            return redirect()->back()->with('accepted', 'Friend Added');
                        }
                    }
                } else if ($friendshipExists) {
                    $friendRequest->delete();
                    return redirect()->back()->with('accepted', 'You Have Already Added This Person as a Friend');
                }
            } else {
                return redirect()->back();
            }
        } else {
            return redirect()->back();
        }
    }

    public function store($targetUserID) {
        $currentUserID = \Auth::user()->id;

        $friendRequest = new FriendRequest();
        $friendRequest->user1_id = $currentUserID;
        $friendRequest->user2_id = $targetUserID;
        $friendRequest->save();
    }

    /*
     * Returns the number of friend requests for the current user.
     *
     * @return integer The number of friend requests.
     */
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
