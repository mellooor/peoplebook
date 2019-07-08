<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Status;
use Illuminate\Support\Facades\Auth;

class pagesController extends Controller
{
    public function notifications() {
        return view('notifications');
    }

    public function user($id = null) {
        if ($user = User::getFromRouteParameter($id)) {
            return view('user')->with('user', $user);
        } else {
            return redirect()->route('home');
        }
    }

    public function friendRequests() {
        return view('friend-requests');
    }

    public function settings() {
        return view('settings');
    }

    public function friends($id = null) {
        if ($user = User::getFromRouteParameter($id)) {
            return view('friends')->with('user', $user);
        } else {
            return redirect()->route('home');
        }
    }

    public function search($term) {
        $currentUser = User::find(Auth::user()->id);

        $userMatches = User::query()
            ->where(function($q) use ($term, $currentUser) {
                $q->where('first_name', 'LIKE', "%{$term}%")
                    ->where('id', '!=', $currentUser->id); // Exclude the current user.
            })->orWhere(function($q) use ($term, $currentUser) {
                $q->where('last_name', 'LIKE', "%{$term}%")
                    ->where('id', '!=', $currentUser->id); // Exclude the current user.
            })->get();

        $statusMatches = Status::query()
            ->where('content', 'LIKE', "%{$term}%")
            ->where('author_id', '!=', $currentUser->id) // Exclude any statuses that were created by the current user
            ->get();

//        $pageMatches = ...


        // Filter the search collections that are affected by privacy settings.
        $userMatches = $currentUser->filterUsersFromCollection($userMatches);
        $statusMatches = $currentUser->filterStatusesFromCollection($statusMatches);

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
}
