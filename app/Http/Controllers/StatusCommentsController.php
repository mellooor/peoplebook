<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Status;
use App\StatusComment;
use Illuminate\Support\Facades\DB;

class StatusCommentsController extends Controller
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
        $currentUserID = \Auth::user()->id;

        $request->validate([
           'status-id' => 'integer|required',
           'comment' => 'string|required|max:16777215'
        ]);

        $statusID = intval($request->input('status-id'));
        $comment = $request->input('comment');

        if ($status = Status::find($statusID)) {
            $statusComment = new StatusComment();
            $statusComment->author_id = $currentUserID;
            $statusComment->status_id = $status->id;
            $statusComment->content = $comment;
            $statusComment->created_at = DB::raw('now()');

            if ($statusComment->save()) {
                return redirect()->back();
            } else {
                return redirect()->back()->with('not-commented', 'An Error Occurred when Adding the Comment. Please try Again');
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
    public function update(Request $request)
    {
        $currentUserID = \Auth::user()->id;

        $request->validate([
           'status-id' => 'integer|required',
           'comment-id' => 'integer|required',
           'comment' => 'string|required|max:16777215'
        ]);

        $statusID = intval($request->input('status-id'));
        $commentID = intval($request->input('comment-id'));
        $comment = $request->input('comment');

        // If the status ID from the request relates to an existing status.
        if ($status = Status::find($statusID)) {
            // If the comment ID from the request relates to an existing comment.
            if ($statusComment = StatusComment::find($commentID)) {
                // If the current user ID matches the author ID of the comment that is being edited.
                if ($statusComment->author_id === $currentUserID) {
                    $statusComment->content = $comment;
                    $statusComment->updated_at = DB::raw('now()');

                    // If the comment updates successfully.
                    if ($statusComment->save()) {
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
        $currentUserID = \Auth::user()->id;

        $request->validate([
           'status-id' => 'integer|required',
           'comment-id' => 'integer|required',
        ]);

        $statusID = intval($request->input('status-id'));
        $commentID = intval($request->input('comment-id'));

        if ($status = Status::find($statusID)) {
            if ($statusComment = StatusComment::find($commentID)) {
                if ($statusComment->author_id === $currentUserID) {
                    if ($statusComment->delete()) {
                        return redirect()->back();
                    } else {
                        return redirect()->back()->with('status-comment-not-deleted', 'An Error Occurred when Deleting the Comment. Please try Again');
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
