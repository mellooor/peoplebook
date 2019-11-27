<?php

namespace App\Http\Controllers;

use App\Activity;
use App\Notification;
use App\PlaceName;
use App\RelationshipRequest;
use App\RelationshipType;
use App\SchoolName;
use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Auth;
use App\Place;
use App\School;
use App\Company;
use App\Job;
use App\Relationship;

class UsersController extends Controller
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
    public function destroy($id)
    {
        //
    }

    /*
     * Update the first_name and last_name fields for the current user.
     *
     * @param \Illuminate\Http\Request $request - The HTTP request.
     * $return \Illuminate\Http\Response - The HTTP response.
     */
    public function updateName(Request $request) {
        $currentUser = User::find(Auth::user()->id);

        $request->validate([
            'first-name' => 'required|string|max:16777215',
            'last-name' => 'required|string|max:16777215'
        ]);

        $firstName = $request->input('first-name');
        $lastName = $request->input('last-name');

        $currentUser->first_name = $firstName;
        $currentUser->last_name = $lastName;

        if ($currentUser->save()) {
            return redirect()->back()->with('success', 'Name updated successfully.');
        } else {
            return redirect()->back()->with('fail', 'An error occurred when updating your name.');
        }
    }

    /*
     * Update the date_of_birth field for the current user.
     *
     * @param \Illuminate\Http\Request $request - The HTTP request.
     * @return \Illuminate\Http\Response - The HTTP response.
     */
    public function updateDOB(Request $request) {
        $currentUser = User::find(Auth::user()->id);
        $today = date('Y-m-d');


        $request->validate([
            'date-of-birth' => 'required|date|before:' . $today
        ]);

        $formattedDate = date('Y-m-d', strtotime($request->input('date-of-birth')));

        $currentUser->date_of_birth = $formattedDate;

        if ($currentUser->save()) {
            return redirect()->back()->with('success', 'Date of birth updated successfully.');
        } else {
            return redirect()->back()->with('fail', 'An error occurred when updating your date of birth.');
        }
    }

    /*
     * Update the home_town_id or current_town_id field for the current user.
     *
     * @param \Illuminate\Http\Request $request - The HTTP request.
     * @param string $placeType - The type of place to update. Can only be 'home' or 'current'.
     * @return \Illuminate\Http\Response - The HTTP response.
     */
    public function updateTown(Request $request, string $placeType) {
        $currentUser = User::find(Auth::user()->id);

        $request->validate([
            'place-name-id' => 'required|integer'
        ]);

        $placeNameID = intval($request->input('place-name-id'));

        // If the town ID corresponds to an actual place in the place names DB table...
        // Validation of placeType parameter also performed.
        if (PlaceName::find($placeNameID) && ($placeType === 'home' || $placeType === 'current')) {
            // Add record in the Places DB table if User hasn't previously lived at the place.
            if (Place::where('user_id', '=', $currentUser->id)->where('place_name_id', '=', $placeNameID)->count() === 0) {
                $placeToAdd = new Place();
                $placeToAdd->user_id = $currentUser->id;
                $placeToAdd->place_name_id = $placeNameID;

                if ($placeToAdd->save()) {
                    $placeID = $placeToAdd->id;
                } else {
                    return redirect()->back()->with('fail', 'An error occurred when updating your town.');
                }
            } else {
                $placeID = Place::where('user_id', '=', $currentUser->id)->where('place_name_id', '=', $placeNameID)->first()->id;
                $placeToAdd = null;
            }

            try {
                if ($placeType === 'home') {
                    $currentUser->home_town_id = $placeID;
                } elseif ($placeType === 'current') {
                    $currentUser->current_town_id = $placeID;
                }

                if ($currentUser->save()) {
                    return redirect()->back()->with('success', 'Town updated successfully.');
                } else {
                    return redirect()->back()->with('fail', 'An error occurred when updating your town.');
                }
            } catch (\Exception $e) {
                if ($placeToAdd) { $placeToAdd->delete(); }
                return redirect()->back()->with('fail', $e->getMessage());
            }
        }
    }

    /*
     * Update the current_school_id field for the current user.
     *
     * @param \Illuminate\Http\Request $request - The HTTP request.
     * @return \Illuminate\Http\Response - The HTTP response.
     */
    public function updateSchool(Request $request) {
        $currentUser = User::find(Auth::user()->id);

        $request->validate([
            'school-name-id' => 'required|integer'
        ]);

        $schoolNameID = $request->input('school-name-id');

        // If the school name ID corresponds to an actual school in the school names DB table...
        if (SchoolName::find($schoolNameID)) {
            if (School::where('user_id', '=', $currentUser->id)->where('school_id', '=', $schoolNameID)->count() === 0) {
                $schoolToAdd = new School();
                $schoolToAdd->user_id = $currentUser->id;
                $schoolToAdd->school_id = $schoolNameID;
                $schoolToAdd->start_date = date('Y-m-d');

                if ($schoolToAdd->save()) {
                    $schoolID = $schoolToAdd->id;
                } else {
                    return redirect()->back()->with('fail', 'An error occurred when updating your school.');
                }
            } else {
                $schoolID = School::where('user_id', '=', $currentUser->id)->where('school_id', '=', $schoolNameID)->first()->id;
                $schoolToAdd = null;
            }

            try {
                $currentUser->current_school_id = $schoolID;

                if ($currentUser->save()) {
                    return redirect()->back()->with('success', 'School updated successfully.');
                } else {
                    if ($schoolToAdd) { $schoolToAdd->delete(); }
                    return redirect()->back()->with('fail', 'An error occurred when updating your school.');
                }
            } catch (\Exception $e) {
                if ($schoolToAdd) { $schoolToAdd->delete(); }
                return redirect()->back()->with('fail', $e->getMessage());
            }
        } else {
            return redirect()->back();
        }
    }

    /*
     * Update the current_job_id field for the current user.
     *
     * @param \Illuminate\Http\Request $request - The HTTP request.
     * @return \Illuminate\Http\Response - The HTTP response.
     */
    public function updateJob(Request $request) {
        $currentUser = User::find(Auth::user()->id);

        $request->validate([
            'employer-id' => 'required|integer',
            'job-title' => 'string|max:16777215'
        ]);

        $employerID = $request->input('employer-id');
        $jobTitle = $request->input('job-title');

        // If the school name ID corresponds to an actual school in the school names DB table...
        if (Company::find($employerID)) {
            // If there's no record in the jobs DB table of the user doing the job for the employer...
            if (Job::where('user_id', '=', $currentUser->id)->where('employer_id', '=', $employerID)->where('job_title', '=', $jobTitle)->count() === 0) {
                $jobToAdd = new Job();
                $jobToAdd->user_id = $currentUser->id;
                $jobToAdd->employer_id = $employerID;
                $jobToAdd->job_title = ($jobTitle) ? $jobTitle : null;

                if ($jobToAdd->save()) {
                    $jobID = $jobToAdd->id;
                } else {
                    return redirect()->back()->with('fail', 'An error occurred when updating your job.');
                }
            } else {
                $jobID = Job::where('user_id', '=', $currentUser->id)->where('employer_id', '=', $employerID)->where('job_title', '=', $jobTitle)->first()->id;
                $jobToAdd = null;
            }

            try {
                $currentUser->current_job_id = $jobID;

                if ($currentUser->save()) {
                    return redirect()->back()->with('success', 'Job updated successfully.');
                } else {
                    if ($jobToAdd) { $jobToAdd->delete(); }
                    return redirect()->back()->with('fail', 'An error occurred when updating your Job.');
                }
            } catch (\Exception $e) {
                if ($jobToAdd) { $jobToAdd->delete(); }
                return redirect()->back()->with('fail', $e->getMessage());
            }
        }
    }

    /*
     * Update the relationship for the current user, or create one if one isn't already set.
     *
     * @param \Illuminate\Http\Request $request - The HTTP request.
     * @return \Illuminate\Http\Response - The HTTP response.
     */
    public function updateRelationship(Request $request) {
        $currentUser = User::find(Auth::user()->id);

        $request->validate([
            'relationship-status' => 'required|string|max:16777215',
            'other-user-id' => 'string|max:16777215'
        ]);

        $singleRelationshipTypeID = RelationshipType::where('type', '=', 'single')->first()->id;
        // Included a commented out variable to store the ID of the regular relationship type in case more relationship types are added in the future.
        //$regularRelationshipTypeID = RelationshipType::where('type', '=', 'relationship')->first()->id;

        $relationshipStatusID = intval($request->input('relationship-status'));
        $otherUserID = intval($request->input('other-user-id'));

        $existingRelationship = $currentUser->relationship();
        $existingRelationshipRequest = $currentUser->relationshipRequest;
        $existingRelationshipActivity = ($existingRelationship) ? Activity::where('new_relationship_id', '=', $existingRelationship->id)->first() : null;
        $existingRelationshipRequestActivity = ($existingRelationshipRequest) ? Activity::where('relationship_request_id', '=', $existingRelationshipRequest->id)->first() : null;

        $relationshipToAdd = new Relationship();

        // If a other user value is supplied...
        if ($otherUserID) {
            // If the supplied other user value corresponds to a real user...
            if (User::find($otherUserID)) {
                // Relationship types of single cannot involve another user.
                if ($relationshipStatusID === $singleRelationshipTypeID) {
                    return redirect()->back()->with('fail', 'A relationship type of single cannot involve another user.');
                }

                // If the current user is already in a relationship with the other user, don't add the relationship again.
                if ($existingRelationship) {
                    if ($existingRelationship->exists($currentUser->id, $otherUserID)) {
                        return redirect()->back()->with('fail', 'You are already in a relationship with this user.');
                    }
                }

                $relationshipRequestToAdd = new RelationshipRequest();
                $relationshipRequestToAdd->user1_id = $currentUser->id;
                $relationshipRequestToAdd->user2_id = $otherUserID;

                if ($relationshipRequestToAdd->save()) {
                    // Create relationship request Activity/Notification.
                    try {
                        if ($existingRelationship) { $existingRelationship->delete(); }
                        if ($existingRelationshipRequest) { $existingRelationshipRequest->delete(); }

                        $activityToAdd = new Activity();
                        $notificationToAdd = new Notification();

                        $activityToAdd->user1_id = $currentUser->id;
                        $activityToAdd->user2_id = $otherUserID;
                        $activityToAdd->relationship_request_id = $relationshipRequestToAdd->id;
                        $activityToAdd->created_at = date('Y-m-d H:i:s');

                        if ($activityToAdd->save()) {
                            $notificationToAdd->user_id = $otherUserID;
                            $notificationToAdd->type_id = 5; // Relationship Request
                            $notificationToAdd->is_active = true;
                            $notificationToAdd->activity_id = $activityToAdd->id;

                            if ($notificationToAdd->save()) {
                                // Change the user's relationship information to be single whilst the relationship request is pending.
                                $relationshipToAdd->start_date = null;
                                $relationshipToAdd->relationship_type_id = 1;
                                $relationshipToAdd->user1_id = $currentUser->id;
                                $relationshipToAdd->user2_id = null;

                                if ($relationshipToAdd->save()) {
                                    // Delete previous relationship, if one existed.
                                    if ($existingRelationship) { $existingRelationship->delete(); }

                                    return redirect()->back()->with('success', 'Your relationship status has been updated, pending confirmation from the other user.');
                                } else {
                                    return redirect()->back()->with('fail', 'An error occurred when updating your relationship status.');
                                }
                            }
                        }


                    } catch (\Exception $e) {
                        // Delete any entities that may have been created before the error occurs.
                        if ($activityToAdd) { $activityToAdd->delete(); }
                        if ($notificationToAdd) { $notificationToAdd->delete(); }

                        // Revert any relationship data to it's original state, if there was any.
                        if ($relationshipToAdd) { $relationshipToAdd->delete(); }
                        if ($existingRelationship) { $existingRelationship->save(); }
                        if ($relationshipRequestToAdd) { $relationshipRequestToAdd->delete(); }
                        if ($existingRelationshipRequest) { $existingRelationshipRequest->save(); }

                        // Revert any activity data back to its original state, if one existed.
                        if ($existingRelationshipActivity) { $existingRelationshipActivity->save(); }

                        // Revert any notification data back to its original state, if one existed.
                        if ($existingRelationshipRequestActivity) { $existingRelationshipRequestActivity->save(); }

                        return redirect()->back()->with('fail', $e->getMessage());
                    }
                } else {
                    return redirect()->back()->with('fail', 'An error occurred when updating your relationship status.');
                }
            } else {
                return redirect()->back();
            }
        } else {
            // If another user value isn't supplied, a relationship cannot be saved unless it is being changed to 'single'.
            if ($relationshipStatusID === $singleRelationshipTypeID) {
                $relationshipToAdd->start_date = null;
                $relationshipToAdd->relationship_type_id = $relationshipStatusID;
                $relationshipToAdd->user1_id = $currentUser->id;
                $relationshipToAdd->user2_id = null;

                if ($relationshipToAdd->save()) {
                    // Delete previous relationship and relationship request, if either existed.
                    if ($existingRelationship) { $existingRelationship->delete(); }
                    if ($existingRelationshipRequest) { $existingRelationshipRequest->delete(); }

                    return redirect()->back()->with('success', 'Your relationship status has been updated');
                } else {
                    return redirect()->back()->with('fail', 'An error occurred when updating your relationship status.');
                }
            } else {
                return redirect()->back()->with('fail', 'Please select another user when updating your relationship status to a relationship of this type.');
            }
        }

    }
}
