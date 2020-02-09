<?php

namespace App\Http\Controllers;

use App\PrivacyType;
use App\Status;
use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class SettingsController extends Controller
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
        //
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
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updatePrivacy(Request $request)
    {
        $currentUser = User::find(Auth::user()->id);

        $request->validate([
            'status-privacy' => Rule::in(['public', 'friends-of-friends', 'friends-only']),
            'photo-privacy' => Rule::in(['public', 'friends-of-friends', 'friends-only']),
            'home-town-privacy' => Rule::in(['public', 'friends-of-friends', 'friends-only']),
            'current-town-privacy' => Rule::in(['public', 'friends-of-friends', 'friends-only']),
            'school-privacy' => Rule::in(['public', 'friends-of-friends', 'friends-only']),
            'job-privacy' => Rule::in(['public', 'friends-of-friends', 'friends-only']),
            'relationship-privacy' => Rule::in(['public', 'friends-of-friends', 'friends-only'])
        ]);

        try {
            if ($newStatusPrivacyType = $request->input('status-privacy')) {
                $originalStatuses = $currentUser->statuses;
                $newStatusPrivacyTypeID = PrivacyType::where('visibility', '=', $newStatusPrivacyType)->first()->id;

                // Update privacy setting for all existing statuses.
                foreach ($originalStatuses as $status) {
                    $status->privacy_type_id = $newStatusPrivacyTypeID;
                    $status->save();
                }

                // Update default status privacy setting.
                $currentUser->default_status_privacy_type_id = $newStatusPrivacyTypeID;
            }

            if ($newPhotoPrivacyType = $request->input('photo-privacy')) {
                $originalPhotos = $currentUser->photos;
                $newPhotoPrivacyTypeID = PrivacyType::where('visibility', '=', $newPhotoPrivacyType)->first()->id;

                // Update privacy setting for all existing photos that aren't a profile picture.
                foreach ($originalPhotos as $photo) {
                    if (!$photo->hasProfilePicture()) {
                        $photo->privacy_type_id = $newPhotoPrivacyTypeID;
                        $photo->save();
                    }
                }

                // Update default photo privacy setting.
                $currentUser->default_photo_privacy_type_id = $newPhotoPrivacyTypeID;
            }

            if ($newHomeTownPrivacyType = $request->input('home-town-privacy')) {
                $newHomeTownPrivacyTypeID = PrivacyType::where('visibility', '=', $newHomeTownPrivacyType)->first()->id;

                // Update home town privacy setting.
                $currentUser->home_town_privacy_type_id = $newHomeTownPrivacyTypeID;
            }

            if ($newCurrentTownPrivacyType = $request->input('current-town-privacy')) {
                $newCurrentTownPrivacyTypeID = PrivacyType::where('visibility', '=', $newCurrentTownPrivacyType)->first()->id;

                // Update current town privacy setting.
                $currentUser->current_town_privacy_type_id = $newCurrentTownPrivacyTypeID;
            }

            if ($newSchoolPrivacyType = $request->input('school-privacy')) {
                $newSchoolPrivacyTypeID = PrivacyType::where('visibility', '=', $newSchoolPrivacyType)->first()->id;

                // Update school privacy setting.
                $currentUser->school_privacy_type_id = $newSchoolPrivacyTypeID;
            }

            if ($newJobPrivacyType = $request->input('job-privacy')) {
                $newJobPrivacyTypeID = PrivacyType::where('visibility', '=', $newJobPrivacyType)->first()->id;

                // Update jon privacy setting.
                $currentUser->job_privacy_type_id = $newJobPrivacyTypeID;
            }

            if ($newRelationshipPrivacyType = $request->input('relationship-privacy')) {
                $newRelationshipPrivacyTypeID = PrivacyType::where('visibility', '=', $newRelationshipPrivacyType)->first()->id;

                // Update relationship privacy setting.
                $currentUser->relationship_privacy_type_id = $newRelationshipPrivacyTypeID;
            }

            if ($currentUser->save()) {
                return redirect()->back()->with('success', 'Your settings have been updated.');
            } else {
                // Restore photo and status privacy type IDs, if they have been changed.
                if ($request->input('status-privacy')) {
                    foreach ($originalStatuses as $status) {
                        $status->save();
                    }
                }

                if ($request->input('photo-privacy')) {
                    foreach ($originalPhotos as $photo) {
                        $photo->save();
                    }
                }

                return redirect()->back()->with('error', 'An error occurred when updating your settings.');
            }
        } catch (\Exception $e) {
            // Restore photo and status privacy type IDs, if they have been changed.
            if ($request->input('status-privacy')) {
                foreach ($originalStatuses as $status) {
                    $status->save();
                }
            }

            if ($request->input('photo-privacy')) {
                foreach ($originalPhotos as $photo) {
                    $photo->save();
                }
            }

            return redirect()->back()->with('error', 'An error occurred when updating your settings.');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
