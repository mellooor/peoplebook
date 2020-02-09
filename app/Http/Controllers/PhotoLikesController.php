<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\User;
use App\Photo;
use App\PhotoLike;
use App\Activity;
use App\Notification;

class PhotoLikesController extends Controller
{
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
            'photo-id' => 'integer|required'
        ]);

        $photoID = intval($request->input('photo-id'));

        // If the status ID from the request points to an existing status from the statuses DB table.
        if ($photo = Photo::find($photoID)) {
            // Get Raw Photo
            $rawPhoto = $photo->getAssociatedPhoto('original-upload');

            if (!$rawPhoto) {
                return redirect()->back()->with('not-liked', 'An Error Occurred when Liking the Photo. Please Try Again');
            }

            // If the user hasn't already liked the status
            if (!$photo->likes->contains('user_id', $currentUser->id)) {
                $photoLike = new PhotoLike();
                $photoLike->user_id = $currentUser->id;
                $photoLike->raw_photo_id = $rawPhoto->id;

                if (!$photoLike->save()) {
                    return redirect()->back()->with('not-liked', 'An Error Occurred when Liking the Photo. Please Try Again');
                }

                // Save Activity
                try {
                    $activity = new Activity();
                    $activity->user1_id = $photoLike->user_id;
                    $activity->user2_id = $photo->uploader_id;
                    $activity->photo_like_id = $photoLike->id;
                    $activity->created_at = date('Y-m-d H:i:s');

                    if (!$activity->save()) {
                        $photoLike->delete();
                        return redirect()->back()->with('not-liked', 'An Error Occurred when Liking the Photo. Please Try Again');
                    }
                } catch (\Exception $e) {
                    $photoLike->delete();
                    return redirect()->back()->with('not-liked', 'An Error Occurred when Liking the Photo. Please Try Again');
                }

                // Save Notification if the user that is liking the status isn't the author of the status itself.
                if ($photoLike->user_id !== $photo->uploader_id) {
                    try {
                        $notification = new Notification();
                        $notification->user_id = $photo->uploader_id;
                        $notification->type_id = 8; // photo-liked.
                        $notification->is_active = true;
                        $notification->activity_id = $activity->id;

                        if (!$notification->save()) {
                            $photoLike->delete();
                            $activity->delete();
                            return redirect()->back()->with('not-liked', 'An Error Occurred when Liking the Photo. Please Try Again');
                        }
                    } catch (\Exception $e) {
                        $photoLike->delete();
                        $activity->delete();
                        return redirect()->back()->with('not-liked', 'An Error Occurred when Liking the Photo. Please Try Again');
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
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $currentUser = User::find(Auth::user()->id);

        $request->validate([
            'photo-like-id' => 'integer|required'
        ]);

        $photoLikeID = intval($request->input('photo-like-id'));

        if ($photoLike = PhotoLike::find($photoLikeID)) {
            if ($photoLike->user_id === $currentUser->id) {
                if ($photoLike->delete()) {
                    return redirect()->back();
                } else {
                    return redirect()->back()->with('not-unliked', 'An Error Occured when Unliking the Photo. Please try Again');
                }
            } else {
                return redirect()->back();
            }
        } else {
            return redirect()->back();
        }
    }
}
