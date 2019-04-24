<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Friendship;
use App\Status;
use Illuminate\Support\Facades\DB;

class StatusesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (\Auth::check()) {
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
        } else {
            return view('welcome');
        }
    }

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
           'body' => 'required|string|max:16777215'
        ]);

        $status = new Status();
        $status->author_id = $currentUserID;
        $status->content = $request->input('body');
        $status->created_at = DB::raw('now()');
        if ($status->save()) {
            return redirect()->back()->with('created', 'Your Status has Been Shared');
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
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
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
