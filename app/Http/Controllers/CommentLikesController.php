<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
Use App\StatusComment;
Use App\CommentLike;

class CommentLikesController extends Controller
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
                $commentLike = new CommentLike();
                $commentLike->user_id = $currentUserID;
                $commentLike->comment_id = $comment->id;

                // If the comment like is successfully saved in the comment likes DB table.
                if ($commentLike->save()) {
                    return redirect()->back();
                } else {
                    return redirect()->back()->with('comment-not-liked', 'An Error Occurred when Liking the Comment. Please try Again');
                }
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
            if ($commentLike = CommentLike::find($commentLikeID)) {
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
