<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Status;
use App\StatusPhoto;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Traits\ImagePathsTrait;
use App\Traits\ImageUploadTrait;
use App\Traits\StoreUploadAndThumbnailTrait;
use App\Activity;

class StatusesController extends Controller
{
    use ImagePathsTrait;
    use ImageUploadTrait;
    use StoreUploadAndThumbnailTrait;

    /**
     * Display all statuses for a given user's status feed.
     *
     * The statuses that are retrieved for a user depends on the privacy settings of the statuses and the user's friends.
     *
     * @return \Illuminate\Http\Response - The HTTP response that correlates to the success of showing the statuses.
     */
    public function index()
    {
        $currentUserID = \Auth::user()->id;
        $friendships = User::find($currentUserID)->friendshipUsers1;
        $friendships = $friendships->merge(User::find($currentUserID)->friendshipUsers2);

        $statuses = User::find($currentUserID)->statuses;

        foreach ($friendships as $friendship) {
            if ($friendship->user1_id === $currentUserID) {
                $statuses = $statuses->merge($friendship->user2->statuses);
            } else if ($friendship->user2_id === $currentUserID) {
                $statuses = $statuses->merge($friendship->user1->statuses);
            }
        }

        $sortedStatuses = $statuses->sortByDesc('created_at');

        $data = [
         'currentUserID' => $currentUserID,
         'statuses' => $sortedStatuses
        ];

        return view('home')->with('data', $data);
    }

    /**
     * Store a newly created status, along with any status photos if they are also present.
     *
     * Validates both the status body and all of the status images before inserting the status to the Statuses
     * DB table, uploading the images and inserting the image information to the Photos and StatusPhotos DB tables.
     * If an error occurs whilst the image is uploading or when it is being inserted into the Photos/StatusPhotos
     * tables, the newly created status is removed before the error is returned.
     *
     * @param  \Illuminate\Http\Request  $request - The HTTP request that is to be processed.
     * @return \Illuminate\Http\Response - The HTTP response that correlates to the success of storing a new status.
     */
    public function store(Request $request)
    {
        $currentUserID = \Auth::user()->id;

        $request->validate([
           'body' => 'required|string|max:16777215'
        ]);

        $validator = Validator::make($request->all(), [
            'status_images.*' => 'mimes:jpg,jpeg,png|max:20000'
        ]);

        if (!$validator->fails()) {
            $status = new Status();
            $status->author_id = $currentUserID;
            $status->content = $request->input('body');
            $status->created_at = DB::raw('now()');

            // if status saved...
            if ($status->save()) {
                // Save Activity
                try {
                    $activity = new Activity();
                    $activity->user1_id = $status->author_id;
                    $activity->created_status_id = $status->id;
                    $activity->created_at = DB::raw('now()');

                    if (!$activity->save()) {
                        $status->delete();
                        return redirect()->back()->with('not-created', 'There was an Error With Sharing Your Status');
                    }
                } catch (\Exception $e) {
                    $status->delete();
                    return redirect()->back()->with('not-created', 'There was an Error With Sharing Your Status');
                }

                if ($request->hasFile('status_images')) {
                    // Error handling is performed to ensure that the newly created status is deleted if an error occurs.
                    try {
                        foreach ($request->file('status_images') as $image) {
                            $uploadedImage = $this->uploadImage($image);
                            $thumbnailImage = $this->createThumbnail($uploadedImage->basename);

                            if ($imageArray = $this->storeUploadAndThumbnail($uploadedImage, $thumbnailImage)) {
                                $uploadPhoto = $imageArray['uploadPhoto'];
                                $thumbnailPhoto = $imageArray['thumbnailPhoto'];

                                // Insert Image name to StatusPhotos table
                                $statusPhoto = new StatusPhoto();
                                $statusPhoto->status_id = $status->id;
                                $statusPhoto->photo_id = $uploadPhoto->id;

                                if (!$statusPhoto->save()) {
                                    // If the status photo isn't inserted correctly...
                                    $status->delete();
                                    $activity->delete();
                                    $uploadPhoto->delete();
                                    $thumbnailPhoto->delete();
                                    return redirect()->back()->with('not-created', 'There was an Error With Sharing Your Status');
                                }
                            } else { // If the photo isn't inserted correctly...
                                $status->delete();
                                $activity->delete();
                                return redirect()->back()->with('not-created', 'There was an Error With Sharing Your Status');
                            }
                        }
                    } catch (\Exception $e) {
                            $status->delete();
                            $activity->delete();
                            return redirect()->back()->with('not-created', $e);
                    }

                    return redirect()->back()->with('created', 'Your Status has Been Shared');
                } else {
                    return redirect()->back()->with('created', 'Your Status has Been Shared');
                }
            } else {
                return redirect()->back()->with('not-created', 'There was an Error With Sharing Your Status');
            }
        } else {
            return redirect()->back()->with('not-created', 'There was an Error With Sharing Your Status');
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
        if ($status = Status::find($id)) {
            return view('status')->with('status', $status);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * Also verifies that the current user is the author of the status that is to be updated after validating the inputs.
     *
     * @param  \Illuminate\Http\Request  $request - The HTTP Request.
     * @return \Illuminate\Http\Response - The HTTP that correlates to the success of updating the status.
     */
    public function update(Request $request)
    {
        $currentUserID = \Auth::user()->id;

        $request->validate([
            'status-id' => 'required|integer',
            'body' => 'required|string|max:16777215'
        ]);

        $statusID = intval($request->input('status-id'));
        $body = $request->input('body');

        // Verify that the status to be updated exists.
        if ($status = Status::find($statusID)) {
            // Verify that the current user is the author of the status.
            if ($status->author_id === $currentUserID) {
                $status->content = $body;
                $status->updated_at = DB::raw('now()');
                if ($status->save()) {
                    return redirect()->back()->with('updated', 'Post Updated');
                } else {
                    return redirect()->back()->with('not-updated', 'An Error Occurred when Updating the Status. Please try Again');
                }
            } else {
                redirect()->back();
            }
        } else {
            return redirect()->back();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * Also verifies that the current user is the author of the status in question before it is deleted.
     *
     * @param  \Illuminate\Http\Request $request - The HTTP request.
     * @return \Illuminate\Http\Response - The HTTP response that correlates to the success of destroying the status.
     */
    public function destroy(Request $request)
    {
        $currentUserID = \Auth::user()->id;

        $request->validate([
           'status-id' => 'required|integer'
        ]);

        $statusID = intval($request->input('status-id'));

        if ($status = Status::find($statusID)) {
            // Verify that the current user is the author of the post to be deleted.
            if ($status->author_id === $currentUserID) {
                if ($status->delete()) {
                    return redirect()->back()->with('deleted', 'Post Deleted');
                } else {
                    return redirect()->back()->with('not-deleted', 'An Error Occured when Deleting the Post. Please try Again');
                }
            } else {
                return redirect()->back();
            }
        } else {
            return redirect()->back();
        }
    }
}
