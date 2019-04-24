<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Status;
use App\StatusLike;

class StatusLikesController extends Controller
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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $currentUserID = \Auth::user()->id;

        $request->validate([
            'status-id' => 'integer|required'
        ]);

        $statusID = intval($request->input('status-id'));

        if (Status::find($statusID)) {
            $statusLike = new StatusLike();
            $statusLike->user_id = $currentUserID;
            $statusLike->status_id = $statusID;
            if ($statusLike->save()) {
                return redirect()->back();
            } else {
                return redirect()->back()->with('not-liked', 'An Error Occurred when Liking the Status. Please Try Again.');
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
            'status-like-id' => 'integer|required'
        ]);

        $statusLikeID = intval($request->input('status-like-id'));

        if ($statusLike = StatusLike::find($statusLikeID)) {
            if ($statusLike->user_id === $currentUserID) {
                if ($statusLike->delete()) {
                    return redirect()->back();
                } else {
                    return redirect()->back()->with('not-unliked', 'An Error Occured when Unliking the Status. Please try Again');
                }
            } else {
                return redirect()->back();
            }
        } else {
            return redirect()->back();
        }
    }
}
