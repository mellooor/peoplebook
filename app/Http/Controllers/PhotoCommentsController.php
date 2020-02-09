<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Photo;
use App\PhotoComment;
use App\Activity;
use App\Notification;
use Illuminate\Support\Facades\Auth;

class PhotoCommentsController extends Controller
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
            'photo-id' => 'integer|required',
            'comment' => 'string|required|max:16777215'
        ]);

        $photoID = intval($request->input('photo-id'));
        $comment = $request->input('comment');

        if ($photo = Photo::find($photoID)) {
            $rawPhoto = $photo->getAssociatedPhoto('original-upload');

            if (!$rawPhoto) {
                return redirect()->back()->with('not-commented', 'There was an Error When Commenting on the Photo');
            }

            $photoComment = new PhotoComment();
            $photoComment->author_id = $currentUser->id;
            $photoComment->raw_photo_id = $rawPhoto->id;
            $photoComment->content = $comment;
            $photoComment->created_at = date('Y-m-d H:i:s');

            if (!$photoComment->save()) {
                return redirect()->back()->with('not-commented', 'There was an Error When Commenting on the Photo');
            }

            // Save Activity
            try {
                $activity = new Activity();
                $activity->user1_id = $photoComment->author_id;
                $activity->user2_id = $photo->uploader_id;
                $activity->photo_comment_id = $photoComment->id;
                $activity->created_at = date('Y-m-d H:i:s');

                if (!$activity->save()) {
                    $photoComment->delete();
                    return redirect()->back()->with('not-commented', 'There was an Error When Commenting on the Photo');
                }
            } catch (\Exception $e) {
                $photoComment->delete();
                return redirect()->back()->with('not-commented', 'There was an Error When Commenting on the Photo');
            }

            // Save Notification if the user that is commenting on the photo isn't the uploader of the photo.
            if ($photoComment->author_id !== $photo->uploader_id) {
                try {
                    $notification = new Notification();
                    $notification->user_id = $photo->uploader_id;
                    $notification->type_id = 7; // photo-commented.
                    $notification->is_active = true;
                    $notification->activity_id = $activity->id;

                    if (!$notification->save()) {
                        $photoComment->delete();
                        $activity->delete();
                        return redirect()->back()->with('not-commented', 'There was an Error When Commenting on the Photo');
                    }
                } catch (\Exception $e) {
                    $photoComment->delete();
                    $activity->delete();
                    return redirect()->back()->with('not-commented', 'There was an Error When Commenting on the Photo');
                }
            }

            return redirect()->back();
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
    public function update(Request $request)
    {
        $currentUser = User::find(Auth::user()->id);

        $request->validate([
            'photo-id' => 'integer|required',
            'comment-id' => 'integer|required',
            'comment' => 'string|required|max:16777215'
        ]);

        $photoID = intval($request->input('photo-id'));
        $commentID = intval($request->input('comment-id'));
        $comment = $request->input('comment');

        // If the photo ID from the request relates to an existing photo...
        if ($photo = Photo::find($photoID)) {
            // If the comment ID from the request relates to an existing comment.
            if ($photoComment = PhotoComment::find($commentID)) {
                // The comment can only be edited if the current user is the author of the comment that is being edited.
                if ($photoComment->author_id === $currentUser->id) {
                    $photoComment->content = $comment;
                    $photoComment->updated_at = date('Y-m-d H:i:s');

                    // If the comment updates successfully.
                    if ($photoComment->save()) {
                        return redirect()->back();
                    } else {
                        return redirect()->back()->with('comment-not-updated', 'An Error Occurred when Updating the Comment. Please try Again.');
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
            'photo-id' => 'integer|required',
            'comment-id' => 'integer|required',
        ]);

        $photoID = intval($request->input('photo-id'));
        $commentID = intval($request->input('comment-id'));

        if ($photo = Photo::find($photoID)) {
            if ($photoComment = PhotoComment::find($commentID)) {
                if ($photoComment->author_id === $currentUser->id) {
                    if ($photoComment->delete()) {
                        return redirect()->back();
                    } else {
                        return redirect()->back()->with('photo-comment-not-deleted', 'An Error Occurred when Deleting the Comment. Please try Again');
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
