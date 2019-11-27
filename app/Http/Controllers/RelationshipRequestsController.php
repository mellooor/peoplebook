<?php

namespace App\Http\Controllers;

use App\Activity;
use App\Notification;
use App\Relationship;
use App\RelationshipRequest;
use App\RelationshipType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\User;

class RelationshipRequestsController extends Controller
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
        $currentUser = User::find(Auth::user()->id);

        if ($relationshipRequest = RelationshipRequest::find($id)) {
            $otherUser = $relationshipRequest->otherUser();

            $data = [
                'relationshipRequest' => $relationshipRequest,
                'otherUser' => $otherUser
            ];

            return view('relationship-request')->with('data', $data);
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

    public function accept(Request $request) {
        $currentUser = User::find(Auth::user()->id);

        $request->validate([
            'relationship-request-id' => 'integer|required',
            'target-user-id' => 'integer|required'
        ]);

        $relationshipRequestID = intval($request->input('relationship-request-id'));
        $targetUserID = intval($request->input('target-user-id'));

        /*
         * If the target relationship request ID matches an existing id in the relationship requests DB table...
         */
        if ($relationshipRequest = RelationshipRequest::find($relationshipRequestID)) {
            if ($relationshipRequest->userIDsMatch($currentUser->id, $targetUserID)) {
                // Retrieve any DB rows from the relationships table involving either user.
                $existingRelationships = Relationship::where('user1_id', '=', $currentUser->id)->orWhere('user2_id', '=', $currentUser->id)->get();
                $existingRelationships = $existingRelationships->merge(Relationship::where('user1_id', '=', $targetUserID)->orWhere('user2_id', '=', $targetUserID)->get());
                $existingRelationshipRequests = RelationshipRequest::where('user1_id', '=', $currentUser->id)->orWhere('user2_id', '=', $currentUser->id)->get();
                $existingRelationshipRequests = $existingRelationshipRequests->merge(RelationshipRequest::where('user1_id', '=', $targetUserID)->orWhere('user2_id', '=', $targetUserID)->get());

                $relationshipBetweenUsersExists = Relationship::exists($currentUser->id, $targetUserID);

                if (!$relationshipBetweenUsersExists) {
                    $relationship = new Relationship();

                    $relationship->start_date = date('Y-m-d');
                    $relationship->relationship_type_id = 2; // relationship
                    $relationship->user1_id = $currentUser->id;
                    $relationship->user2_id = $targetUserID;

                    if ($relationship->save()) {
                        try {
                            $notification = null;

                            $activity = new Activity();
                            $activity->user1_id = $currentUser->id;
                            $activity->user2_id = $targetUserID;
                            $activity->new_relationship_id = $relationship->id;
                            $activity->created_at = date('Y-m-d H:i:s');

                            if ($activity->save()) {
                                // Save Notification for the user that didn't accept the friend request (the one that isn't the current user).
                                $notification = new Notification();
                                $notification->user_id = $targetUserID;
                                $notification->type_id = 6; // relationship-request-accepteed
                                $notification->is_active = 1;
                                $notification->activity_id = $activity->id;

                                if ($notification->save()) {
                                    // Delete all previous instances of a relationship involving either user in the relationships DB table.
                                    if ($existingRelationships) {
                                        foreach ($existingRelationships as $existingRelationship) {
                                            if ($existingRelationship) { $existingRelationship->delete(); }
                                        }
                                    }

                                    // Delete all instances of a relationship request involving either user in the relationship Requests DB table.
                                    if ($existingRelationshipRequests) {
                                        foreach ($existingRelationshipRequests as $existingRelationshipRequest) {
                                            if ($existingRelationshipRequest) { $existingRelationshipRequest->delete(); }
                                        }
                                    }

                                    $relationshipRequest->delete();
                                    return redirect()->back()->with('success', 'Relationship request accepted.');
                                } else {
                                    $relationship->delete();
                                    $activity->delete();
                                    return redirect()->back()->with('fail', 'An error occurred when accepting the relationship request.');
                                }
                            } else {
                                $relationship->delete();
                                return redirect()->back()->with('fail', 'An error occurred when accepting the relationship request.');
                            }
                        } catch (\Exception $e) {
                            // Delete any possibly newly created entities.
                            if ($relationship) { $relationship->delete(); }
                            if ($activity) { $activity->delete(); }
                            if ($notification) { $notification->delete(); }

                            // Restore the relationship request if it has been deleted.
                            if (!RelationshipRequest::find($relationshipRequest->id)) { $relationshipRequest->save(); }

                            // Restore any existing relationships involving either user if they have been deleted.
                            if ($existingRelationships) {
                                foreach ($existingRelationships as $existingRelationship) {
                                    if ($existingRelationship) { $existingRelationship->save(); }
                                }
                            }

                            // Restore any existing relationship requests involving either user if they have been deleted.
                            if ($existingRelationshipRequests) {
                                foreach ($existingRelationshipRequests as $existingRelationshipRequest) {
                                    if ($existingRelationshipRequest) { $existingRelationshipRequest->save(); }
                                }
                            }

                            return redirect()->back()->with('fail', 'An error occurred when accepting the relationship request.');
                        }
                    } else {
                        return redirect()->back()->with('fail', 'An error occurred when accepting the relationship request.');
                    }
                } elseif ($relationshipBetweenUsersExists) {
                    $relationshipRequest->delete();
                    return redirect()->back()->with('fail', 'You are already in a relationship with this user');
                }
            } else {
                return redirect()->back();
            }
        } else {
            return redirect()->back();
        }
    }

    public function decline(Request $request) {
        $currentUser = User::find(Auth::user()->id);

        $request->validate([
            'relationship-request-id' => 'integer|required',
            'target-user-id' => 'integer|required'
        ]);

        $relationshipRequestID = intval($request->input('relationship-request-id'));
        $targetUserID = intval($request->input('target-user-id'));

        /*
         * If the relationship request ID matches an existing id in the relationship requests DB table...
         */
        if ($relationshipRequest = RelationshipRequest::find($relationshipRequestID)) {
            /*
            * If the supplied user IDs match the user IDs in the relationship request record from the DB table...
            */
            if ($relationshipRequest->userIDsMatch($currentUser->id, $targetUserID)) {
                $relationshipRequest->delete();
                return redirect()->back()->with('success', 'Relationship request declined.');
            } else {
                return redirect()->back();
            }
        } else {
            return redirect()->back();
        }
    }
}
