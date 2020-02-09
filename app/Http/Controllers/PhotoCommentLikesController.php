<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\PhotoComment;
use App\PhotoCommentLike;
use App\Activity;
use App\Notification;
use Illuminate\Support\Facades\Auth;

class PhotoCommentLikesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $currentUser = User::find(Auth::user()->id);

        $request->validate([
            'photo-comment-id' => 'integer|required'
        ]);

        $photoCommentID = intval($request->input('photo-comment-id'));

        // If the comment ID from the request matches an existing comment in the status comments DB table.
        if ($photoComment = PhotoComment::find($photoCommentID)) {
            // If the current user hasn't already liked the comment.
            if (!$photoComment->likes->contains('user_id', $currentUser->id)) {
                $photoCommentLike = new PhotoCommentLike();
                $photoCommentLike->user_id = $currentUser->id;
                $photoCommentLike->comment_id = $photoComment->id;

                // If the comment like is successfully saved in the photo comment likes DB table.
                if (!$photoCommentLike->save()) {
                    return redirect()->back()->with('comment-not-liked', 'An Error Occurred when Liking the Comment. Please try Again');
                }

                // Save Activity
                try {
                    $activity = new Activity();
                    $activity->user1_id = $photoCommentLike->user_id;
                    $activity->user2_id = $photoComment->author_id;
                    $activity->photo_comment_like_id = $photoCommentLike->id;
                    $activity->created_at = date('Y-m-d H:i:s');

                    if (!$activity->save()) {
                        $photoCommentLike->delete();
                        return redirect()->back()->with('comment-not-liked', 'An Error Occurred when Liking the Comment. Please try Again');
                    }
                } catch (\Exception $e) {
                    $photoCommentLike->delete();
                    return redirect()->back()->with('comment-not-liked', 'An Error Occurred when Liking the Comment. Please try Again');
                }

                // Save Notification if the user that is liking the comment isn't the author of the comment itself.
                if ($photoCommentLike->user_id !== $photoComment->author_id) {
                    try {
                        $notification = new Notification();
                        $notification->user_id = $photoComment->author_id;
                        $notification->type_id = 9; // photo-comment-liked.
                        $notification->is_active = true;
                        $notification->activity_id = $activity->id;

                        if (!$notification->save()) {
                            $photoCommentLike->delete();
                            $activity->delete();
                            return redirect()->back()->with('comment-not-liked', 'An Error Occurred when Liking the Comment. Please try Again');
                        }
                    } catch (\Exception $e) {
                        $photoCommentLike->delete();
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
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $currentUser = User::find(Auth::user()->id);

        $request->validate([
            'photo-comment-id' => 'integer|required',
            'photo-comment-like-id' => 'integer|required'
        ]);

        $photoCommentID = intval($request->input('photo-comment-id'));
        $photoCommentLikeID = intval($request->input('photo-comment-like-id'));

        // If the comment ID from the request points to an existing comment in the status comments DB table.
        if ($photoComment = PhotoComment::find($photoCommentID)) {
            // If the comment like ID from the request points to an existing comment in the comment likes DB table.
            if ($photoCommentLike = PhotoCommentLike::find($photoCommentLikeID)) {
                if ($photoCommentLike->user_id === $currentUser->id) {
                    // If the comment is successfully deleted from the comment likes DB table.
                    if ($photoCommentLike->delete()) {
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
