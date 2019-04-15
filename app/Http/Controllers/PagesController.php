<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Status;

class pagesController extends Controller
{
    public function notifications() {
        return view('notifications');
    }

    public function user($id = null) {
        return view('user');
    }

    public function friendRequests() {
        return view('friend-requests');
    }

    public function settings() {
        return view('settings');
    }

    public function friends() {
        return view('friends');
    }

    public function search($term) {
        $userMatches = User::query()
            ->where('first_name', 'LIKE', "%{$term}%")
            ->orWhere('last_name', 'LIKE', "%{$term}%")
            ->get();

        $statusMatches = Status::query()
            ->where('content', 'LIKE', "%{$term}%")
            ->get();

//        $pageMatches = ...

        $matchesArray = [
            'term' => $term,
            'users' => $userMatches,
            'statuses' => $statusMatches
        ];

        return view('search')->with('results', $matchesArray);
    }

    public function userMoreInfo($id) {
        return view('more-info')->with('userID', $id);
    }

    public function status($id) {
        return view('status')->with('statusID', $id);
    }

    public function userPhotos($id) {
        return view('photos')->with('userID', $id);
    }
}
