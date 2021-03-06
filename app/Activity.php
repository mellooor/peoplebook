<?php

namespace App;

use App\Traits\FormatDateTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use App\Library\DateTime as PeopleBookDateTime;

class Activity extends Model
{
    public $timestamps = false;
    protected $dates = ['created_at'];

    public function isCreatedStatus() {
        return ($this->created_status_id) ? $this->createdStatus : null;
    }

    public function isStatusCommented() {
        return ($this->status_comment_id) ? $this->statusComment : null;
    }

    public function isStatusLiked() {
        return ($this->status_like_id) ? $this->statusLike : null;
    }

    public function isStatusCommentLiked() {
        return ($this->status_comment_like_id) ? $this->statusCommentLike : null;
    }

    public function isUploadedPhoto() {
        return ($this->uploaded_photo_id) ? $this->uploadedPhoto : null;
    }

    public function isChangedProfilePicture() {
        return ($this->updated_profile_picture_photo_id) ? $this->changedProfilePicture : null;
    }

    public function isNewFriendship() {
        return ($this->new_friendship_id) ? $this->newFriendship : null;
    }

    public function isRelationshipRequest() {
        return ($this->relationship_request_id) ? $this->relationshipRequest : null;
    }

    public function isNewRelationship() {
        return ($this->new_relationship_id) ? $this->relationshipRequestAccepted : null;
    }

    public function isPhotoComment() {
        return ($this->photo_comment_id) ? $this->relationshipRequestAccepted : null;
    }

    public function isPhotoLike() {
        return ($this->photo_like_id) ? $this->relationshipRequestAccepted : null;
    }

    public function isPhotoCommentLike() {
        return ($this->photo_comment_like_id) ? $this->relationshipRequestAccepted : null;
    }

    public function createdStatus() {
        return $this->belongsTo('App\Status', 'created_status_id');
    }

    public function statusComment() {
        return $this->belongsTo('App\StatusComment', 'status_comment_id');
    }

    public function statusLike() {
        return $this->belongsTo('App\StatusLike', 'status_like_id');
    }

    public function statusCommentLike() {
        return $this->belongsTo('App\StatusCommentLike', 'status_comment_like_id');
    }

    public function uploadedPhoto() {
        return $this->belongsTo('App\Photo', 'uploaded_photo_id');
    }

    public function changedProfilePicture() {
        return $this->belongsTo('App\Photo', 'updated_profile_picture_photo_id');
    }

    public function newFriendship() {
        return $this->belongsTo('App\Friendship', 'new_friendship_id');
    }

    public function relationshipRequest() {
        return $this->belongsTo('App\RelationshipRequest', 'relationship_request_id');
    }

    public function relationshipRequestAccepted() {
        return $this->belongsTo('App\Relationship', 'new_relationship_id');
    }

    public function photoComment() {
        return $this->belongsTo('App\PhotoComment', 'photo_comment_id');
    }

    public function photoLike() {
        return $this->belongsTo('App\PhotoLike', 'photo_like_id');
    }

    public function photoCommentLike() {
        return $this->belongsTo('App\PhotoCommentLike', 'photo_comment_like_id');
    }

    public static function removePreviousProfilePictureChangedActivities(Collection $previousProfilePicChangedActivities = null) {
        if ($previousProfilePicChangedActivities) {
            foreach ($previousProfilePicChangedActivities as $previousProfilePicChangedActivity) {
                $previousProfilePicChangedActivity->delete();
            }
        }
    }

    /*
     * Returns the duration up to now since the activity was created in a format set in the Peoplebook
     * DateTime class.
     */
    public function createdAtDuration() {
        return PeopleBookDateTime::formatDuration($this->created_at);
    }

    /*
     * Returns the privacy setting of the activity if it is an activity that is found on the news feed.
     *
     * @param int $targetUserID - The privacy of some activities is saved against the user so the target user ID is supplied to retrieve these.
     *
     * @return string - the privacy setting of the activity. Returns an empty string for activities that aren't found on the news feed.
     */
    public function getPrivacy(User $targetUser) {
        if ($this->isCreatedStatus() || $this->isUploadedPhoto() || $this->isChangedProfilePicture() || $this->isNewFriendship() || $this->isNewRelationship()) {
            if ($createdStatus = $this->isCreatedStatus()) {
                return $createdStatus->privacy->visibility;
            } elseif ($uploadedPhoto = $this->isUploadedPhoto()) {
                return $uploadedPhoto->privacy->visibility;
            } elseif ($changedProfilePicture = $this->isChangedProfilePicture()) {
                return $changedProfilePicture->privacy->visibility;
            } elseif ($newFriendship = $this->isNewFriendship()) {
                return $targetUser->friendsPrivacy->visibility;
            } elseif ($newRelationship = $this->isNewRelationship()) {
                return $targetUser->relationshipPrivacy->visibility;
            } else {
                return '';
            }
        } else {
            return '';
        }
    }
}
