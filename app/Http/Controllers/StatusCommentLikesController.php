<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
Use App\StatusComment;
Use App\StatusCommentLike;
use App\Activity;
use App\Notification;
use Illuminate\Support\Facades\DB;

class StatusCommentLikesController extends Controller
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
            'comment-id' => 'integer|required'
        ]);

        $commentID = intval($request->input('comment-id'));

        // If the comment ID from the request matches an existing comment in the status comments DB table.
        if ($comment = StatusComment::find($commentID)) {
            // If the current user hasn't already liked the comment.
            if (!$comment->likes->contains('user_id', $currentUserID)) {
                $commentLike = new StatusCommentLike();
                $commentLike->user_id = $currentUserID;
                $commentLike->comment_id = $comment->id;

                // If the comment like is successfully saved in the comment likes DB table.
                if (!$commentLike->save()) {
                    return redirect()->back()->with('comment-not-liked', 'An Error Occurred when Liking the Comment. Please try Again');
                }

                // Save Activity
                try {
                    $activity = new Activity();
                    $activity->user1_id = $commentLike->user_id;
                    $activity->user2_id = $comment->author_id;
                    $activity->status_comment_like_id = $commentLike->id;
                    $activity->created_at = DB::raw('now()');

                    if (!$activity->save()) {
                        $commentLike->delete();
                        return redirect()->back()->with('comment-not-liked', 'An Error Occurred when Liking the Comment. Please try Again');
                    }
                } catch (\Exception $e) {
                    $commentLike->delete();
                    return redirect()->back()->with('comment-not-liked', 'An Error Occurred when Liking the Comment. Please try Again');
                }

                // Save Notification if the user that is liking the comment isn't the author of the comment itself.
                if ($commentLike->user_id !== $comment->author_id) {
                    try {
                        $notification = new Notification();
                        $notification->user_id = $comment->author_id;
                        $notification->type_id = 4; // comment-liked.
                        $notification->is_active = true;
                        $notification->activity_id = $activity->id;

                        if (!$notification->save()) {
                            $commentLike->delete();
                            $activity->delete();
                            return redirect()->back()->with('comment-not-liked', 'An Error Occurred when Liking the Comment. Please try Again');
                        }
                    } catch (\Exception $e) {
                        $commentLike->delete();
                        $activity->delete();
                        return redirect()->back()->with('comment-not-liked', 'An Error Occurred when Liking the Comment. Please try Again');
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
            'comment-id' => 'integer|required',
            'comment-like-id' => 'integer|required'
        ]);

        $commentID = intval($request->input('comment-id'));
        $commentLikeID = intval($request->input('comment-like-id'));

        // If the comment ID from the request points to an existing comment in the status comments DB table.
        if ($comment = StatusComment::find($commentID)) {
            // If the comment like ID from the request points to an existing comment in the comment likes DB table.
            if ($commentLike = StatusCommentLike::find($commentLikeID)) {
                if ($commentLike->user_id === $currentUserID) {
                    // If the comment is successfully deleted from the comment likes DB table.
                    if ($commentLike->delete()) {
                        return redirect()->back();
                    } else {
                        return redirect()->back()->with('comment-not-unliked', 'An Error Occurred when unliking the Comment. Please try Again');
                    }
                } else {
                    return redirect()->back();
                }


            } else {
                return redirect()->back();
            }
        } else {
            return redirect()->back();
        }
    }
}
