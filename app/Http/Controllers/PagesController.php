<?php

namespace App\Http\Controllers;

use App\Activity;
use App\Place;
use App\PlaceName;
use App\School;
use App\SchoolName;
use App\User;
use App\Status;
use Illuminate\Support\Facades\Auth;
use App\Company;
use App\RelationshipType;

class pagesController extends Controller
{
    public function notifications() {
        return view('notifications');
    }

    public function user($targetUserID = null) {
        $currentUser = User::find(Auth::user()->id);
        $isCurrentUser = false;

        if (intval($targetUserID) === $currentUser->id) {
            return redirect('my-profile');
        }

        if ($targetUserID === null) { $isCurrentUser = true; }

        // If the supplied user ID parameter corresponds to an actual user...
        if ($targetUser = User::getFromRouteParameter($targetUserID)) {
            $data = ['user' => $targetUser];
            $publicNewsFeedItems = collect();
            $friendOfFriendNewsFeedItems = collect();
            $friendOnlyNewsFeedItems = collect();
            // If the target user isn't the current user, determine whether they are a friend of the current user.
            $isFriend = (!$isCurrentUser) ? $currentUser->isAFriend($targetUserID) : null;
            // If the target user isn't the current user, determine whether they are a friend of a friend of the current user.
            $isFriendOfFriend = (!$isCurrentUser) ? $currentUser->isAFriendOfAFriend($targetUserID) : null;

            $allNewsFeedItems = Activity::where(function($q) use ($targetUser) {
                /*
                 * Get all activities for a user where the activity is one for a status being created, a photo
                 * being uploaded, a profile picture being changed or a new friendship.
                 */
                $q->where('user1_id', '=', $targetUser->id)
                    ->orWhere('user2_id', '=', $targetUser->id);
            })->where(function($q) {
                    $q->where('created_status_id', '!=', null)
                        ->orWhere('uploaded_photo_id', '!=', null)
                        ->orWhere('updated_profile_picture_photo_id', '!=', null)
                        ->orWhere('new_friendship_id', '!=', null)
                        ->orWhere('new_relationship_id', '!=', null);
                })->orderBy('created_at', 'DESC')->get();

            /*
             * Filter the activities based on the privacy settings of them and the relationship of the current user
             * to the user that the user id parameter corresponds to then paginate.
             *
             * The foreach loops are wrapped within isFriendOfFriend and isFriend conditionals to prevent unnecessary
             * privacy conditionals running on every news feed item in the loop.
             */
            if ($isFriendOfFriend) {
                /*
                 * If the target user is a friend of a friend of the current user, filter out all news feed items
                 * apart from those that are set to be seen publicly or by friends of friends.
                 */
                foreach ($allNewsFeedItems as $newsFeedItem) {
                    if ($newsFeedItem->getPrivacy($targetUser) === 'public') {
                        $publicNewsFeedItems->push($newsFeedItem);
                    } elseif ($newsFeedItem->getPrivacy($targetUser) === 'friends-of-friends') {
                        $friendOnlyNewsFeedItems->push($newsFeedItem);
                    }
                }
            } elseif ($isFriend || $isCurrentUser) {
                /*
                 * If the target user is a friend of the current user or is the current user themselves, filter out
                 * all news feed items apart from those that are set to be seen publicly, by friends of friends and
                 * by friends.
                 */
                foreach ($allNewsFeedItems as $newsFeedItem) {
                    if ($newsFeedItem->getPrivacy($targetUser) === 'public') {
                        $publicNewsFeedItems->push($newsFeedItem);
                    } elseif ($newsFeedItem->getPrivacy($targetUser) === 'friends-of-friends') {
                        $friendOnlyNewsFeedItems->push($newsFeedItem);
                    } elseif ($newsFeedItem->getPrivacy($targetUser) === 'friends-only') {
                        $friendOfFriendNewsFeedItems->push($newsFeedItem);
                    }
                }
            } else {
                foreach ($allNewsFeedItems as $newsFeedItem) {
                    if ($newsFeedItem->getPrivacy($targetUser) === 'public') {
                        $publicNewsFeedItems->push($newsFeedItem);
                    }
                }
            }

            // Merge all of the filtered news feed items back together.
            $data['newsFeedItems'] = $publicNewsFeedItems;
            $data['newsFeedItems'] = $data['newsFeedItems']->merge($friendOfFriendNewsFeedItems);
            $data['newsFeedItems'] = $data['newsFeedItems']->merge($friendOnlyNewsFeedItems);

            // Re-sort the news feed items into descending chronological order.
            $data['newsFeedItems'] = $data['newsFeedItems']->sortByDesc('created_at');

            // Paginate the collection.
            $data['newsFeedItems'] = $data['newsFeedItems']->paginate(10);




            return view('user')->with('data', $data);
        } else {
            return redirect()->route('home');
        }
    }

    public function friendRequests() {
        return view('friend-requests');
    }

    public function settings() {
        $currentUser = User::find(Auth::user()->id);
        return view('settings')->with('user', $currentUser);
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

    public function userMoreInfo($id = null) {
       if ($user = User::getFromRouteParameter($id)) {
           $data = [];
           $currentUser = User::find(Auth::user()->id);
           $isLoggedinUser = ($user->id === $currentUser->id);
           $isFriend = (!$isLoggedinUser) ? $user->isAFriend($currentUser->id) : false;
           $isFriendOfFriend = (!$isLoggedinUser) ? $user->isAFriendOfAFriend($currentUser->id) : false;

           if ($isLoggedinUser || $isFriend) {
               $data['displayDOB'] = true;
               $data['displayHomeTown'] = true;
               $data['displayCurrentTown'] = true;
               $data['displaySchool'] = true;
               $data['displayJob'] = true;
               $data['displayRelationship'] = true;
           } else if (!$isLoggedinUser) {
               $dateOfBirthPrivacy = $user->dateOfBirthPrivacy->visibility;
               $homeTownPrivacy = $user->homeTownPrivacy->visibility;
               $currentTownPrivacy = $user->currentTownPrivacy->visibility;
               $schoolPrivacy = $user->schoolPrivacy->visibility;
               $jobPrivacy = $user->jobPrivacy->visibility;
               $relationshipPrivacy = $user->relationshipPrivacy->visibility;

               if ($isFriendOfFriend) {
                    // Is the date of birth to be displayed?
                    $data['displayDOB'] = ($dateOfBirthPrivacy === 'public' || $dateOfBirthPrivacy === 'friends-of-friends');

                    // Is the home town to be displayed?
                    $data['displayHomeTown'] = ($homeTownPrivacy === 'public' || $homeTownPrivacy === 'friends-of-friends');

                    // Is the current town to be displayed?
                    $data['displayCurrentTown'] = ($currentTownPrivacy === 'public' || $currentTownPrivacy === 'friends-of-friends');

                    // Is the school to be displayed?
                    $data['displaySchool'] = ($schoolPrivacy === 'public' || $schoolPrivacy === 'friends-of-friends');

                    // Is the job to be displayed?
                    $data['displayJob'] = ($jobPrivacy === 'public' || $jobPrivacy === 'friends-of-friends');

                    // Is the relationship to be displayed?
                    $data['displayRelationship'] = ($relationshipPrivacy === 'public' || $relationshipPrivacy === 'friends-of-friends');
               } else {
                    // Is the date of birth to be displayed?
                    $data['displayDOB'] = ($dateOfBirthPrivacy === 'public');

                    // Is the home town to be displayed?
                    $data['displayHomeTown'] = ($homeTownPrivacy === 'public');

                    // Is the current town to be displayed?
                    $data['displayCurrentTown'] = ($currentTownPrivacy === 'public');

                    // Is the school to be displayed?
                    $data['displaySchool'] = ($schoolPrivacy === 'public');

                    // Is the job to be displayed?
                    $data['displayJob'] = ($jobPrivacy === 'public');

                    // Is the relationship to be displayed?
                    $data['displayRelationship'] = ($relationshipPrivacy === 'public');
               }
           }

           $data['user'] = $user;

            return view('more-info')->with('data', $data);
       } else {
           return redirect()->route('home');
       }
    }

    public function status($id) {
        return view('status')->with('statusID', $id);
    }

    public function newsFeedIndex() {
        if (\Auth::check()) {
            $currentUserID = Auth::user()->id;
            $currentUser = User::find($currentUserID);
            $data = [];

            $data['newsFeedItems'] = Activity::where(function($q) use ($currentUser) {
                $currentUserFriendIDs = $currentUser->getAllFriendIDs();

                /*
                 * Get all activities where at least one of the users is a friend of the current user, neither of
                 * the users are the current user and where the activity is one for a status being created, a photo
                 * being uploaded, a profile picture being changed, a new friendship or a new relationship.
                 */
                $q->whereIn('user1_id', $currentUserFriendIDs)
                    ->orWhereIn('user2_id', $currentUserFriendIDs);
            })->where('user1_id', '!=', $currentUserID)
                ->where(function($q) use ($currentUserID) {
                $q->where('user2_id', '!=', $currentUserID)
                    ->orWhere('user2_id', '=', null);
            })->where(function($q) {
                $q->where('created_status_id', '!=', null)
                    ->orWhere('uploaded_photo_id', '!=', null)
                    ->orWhere('updated_profile_picture_photo_id', '!=', null)
                    ->orWhere('new_friendship_id', '!=', null)
                    ->orWhere('new_relationship_id', '!=', null);
            })->orderBy('created_at', 'DESC')->paginate(10);

            $data['user'] = $currentUser;


            return view('home')->with('data', $data);
        } else {
            return view('welcome');
        }
    }

    public function editName() {
        $currentUser = User::find(Auth::user()->id);

        return view('more-info-name-edit')->with('user', $currentUser);
    }

    public function editDOB() {
        $currentUser = User::find(Auth::user()->id);

        return view('more-info-dob-edit')->with('user', $currentUser);
    }

    public function editHomeTown() {
        $currentUser = User::find(Auth::user()->id);
        $places = PlaceName::all();

        $data = [
            'user' => $currentUser,
            'places' => $places,
            'type' => 'home'
        ];

        return view('more-info-town-edit')->with('data', $data);
    }

    public function editCurrentTown() {
        $currentUser = User::find(Auth::user()->id);
        $places = PlaceName::all();

        $data = [
            'user' => $currentUser,
            'places' => $places,
            'type' => 'current'
        ];

        return view('more-info-town-edit')->with('data', $data);
    }

    public function editSchool() {
        $currentUser = User::find(Auth::user()->id);
        $schoolNames = SchoolName::all();

        $data = [
          'user' => $currentUser,
          'schoolNames' => $schoolNames
        ];

        return view('more-info-school-edit')->with('data', $data);
    }

    public function editJob() {
        $currentUser = User::find(Auth::user()->id);
        $employers = Company::all();

        $data = [
            'user' => $currentUser,
            'employers' => $employers
        ];

        return view('more-info-job-edit')->with('data', $data);
    }

    public function editRelationship() {
        $currentUser = User::find(Auth::user()->id);
        $relationshipTypes = RelationshipType::all();
        $otherUsers = User::where('id', '!=', $currentUser->id)->get();

        $data = [
          'user' => $currentUser,
          'relationshipTypes' => $relationshipTypes,
          'otherUsers' => $otherUsers
        ];

        return view('more-info-relationship-edit')->with('data', $data);
    }
}
