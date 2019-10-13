<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Status;
use App\StatusLike;
use App\Activity;
use App\Notification;
use Illuminate\Support\Facades\DB;

class StatusLikesController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $currentUserID = \Auth::user()->id;

        $request->validate([
            'status-id' => 'integer|required'
        ]);

        $statusID = intval($request->input('status-id'));

        // If the status ID from the request points to an existing status from the statuses DB table.
        if ($status = Status::find($statusID)) {
            // If the user hasn't already liked the status
            if (!$status->likes->contains('user_id', $currentUserID)) {
                $statusLike = new StatusLike();
                $statusLike->user_id = $currentUserID;
                $statusLike->status_id = $statusID;

                if (!$statusLike->save()) {
                    return redirect()->back()->with('not-liked', 'An Error Occurred when Liking the Status. Please Try Again');
                }

                // Save Activity
                try {
                    $activity = new Activity();
                    $activity->user1_id = $statusLike->user_id;
                    $activity->user2_id = $status->author_id;
                    $activity->status_like_id = $statusLike->id;
                    $activity->created_at = DB::raw('now()');

                    if (!$activity->save()) {
                        $statusLike->delete();
                        return redirect()->back()->with('not-liked', 'An Error Occurred when Liking the Status. Please Try Again');
                    }
                } catch (\Exception $e) {
                    $statusLike->delete();
                    return redirect()->back()->with('not-liked', 'An Error Occurred when Liking the Status. Please Try Again');
                }

                // Save Notification if the user that is liking the status isn't the author of the status itself.
                if ($statusLike->user_id !== $status->author_id) {
                    try {
                        $notification = new Notification();
                        $notification->user_id = $status->author_id;
                        $notification->type_id = 2; // status-liked.
                        $notification->is_active = true;
                        $notification->activity_id = $activity->id;

                        if (!$notification->save()) {
                            $statusLike->delete();
                            $activity->delete();
                            return redirect()->back()->with('not-liked', 'An Error Occurred when Liking the Status. Please Try Again');
                        }
                    } catch (\Exception $e) {
                        $statusLike->delete();
                        $activity->delete();
                        return redirect()->back()->with('not-liked', 'An Error Occurred when Liking the Status. Please Try Again');
                    }
                }

                return redirect()->back();
            } else {
                return redirect()->back();
            }
        } else {
            return redirect()->back();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $currentUserID = \Auth::user()->id;

        $request->validate([
            'status-like-id' => 'integer|required'
        ]);

        $statusLikeID = intval($request->input('status-like-id'));

        if ($statusLike = StatusLike::find($statusLikeID)) {
            if ($statusLike->user_id === $currentUserID) {
                if ($statusLike->delete()) {
                    return redirect()->back();
                } else {
                    return redirect()->back()->with('not-unliked', 'An Error Occured when Unliking the Status. Please try Again');
                }
            } else {
                return redirect()->back();
            }
        } else {
            return redirect()->back();
        }
    }
}
