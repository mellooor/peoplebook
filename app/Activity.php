<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class Activity extends Model
{
    public $timestamps = false;

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

    public static function removePreviousProfilePictureChangedActivities(Collection $previousProfilePicChangedActivities = null) {
        if ($previousProfilePicChangedActivities) {
            foreach ($previousProfilePicChangedActivities as $previousProfilePicChangedActivity) {
                $previousProfilePicChangedActivity->delete();
            }
        }
    }
}
