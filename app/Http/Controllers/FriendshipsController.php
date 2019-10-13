<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Friendship;
use App\User;

class FriendshipsController extends Controller
{
    public function index($id = null) {
        if ($user = User::getFromRouteParameter($id)) {
            $data = ['user' => $user];
            $data['friendships'] = [];

            foreach ($user->getAllFriendships() as $friendship) {
                if ($friendship->user1_id === $user->id) {
                    $data['friendships'][$friendship->id] = $friendship->user2;
                } else if ($friendship->user2_id === $user->id) {
                    $data['friendships'][$friendship->id] = $friendship->user1;
                }
            }

            return view('friends')->with('data', $data);
        } else {
            return redirect()->route('home');
        }
    }

    public function destroy(Request $request) {
        $currentUserID = \Auth::user()->id;

        $request->validate([
            'friendship-id' => 'integer|required',
            'target-user-id' => 'integer|required'
        ]);

        $targetFriendshipID = intval($request->input('friendship-id'));
        $targetUserID = intval($request->input('target-user-id'));

        /*
         * If the target friendship ID matches an existing ID in the friendships DB table...
         */
        if ($friendship = Friendship::find($targetFriendshipID)) {
            /*
             * If the supplied user IDs match the user IDs in the friendship record from the DB table...
             */
            if ($friendship->userIDsMatch($currentUserID, $targetUserID)) {
                $friendship->delete();
                return redirect()->back()->with('removed', 'Removed Friend');
            } else {
                return redirect()->back();
            }
        } else {
            return redirect()->back();
        }
    }
}
