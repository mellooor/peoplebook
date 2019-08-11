<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    public $timestamps = false;

    public function getEntity() {
        if ($this->created_status_id) {
            return $this->createdStatus();
        } else if ($this->status_comment_id) {
            return $this->statusComment();
        } else if ($this->status_like_id) {
            return $this->statusLike();
        } else if ($this->status_comment_like_id) {
            return $this->statusCommentLike();
        } else if ($this->uploaded_photo_id) {
            return $this->uploadedPhoto();
        } else if ($this->updated_profile_picture_photo_id) {
            return $this->changedProfilePicture();
        } else if ($this->new_friendship_id) {
            return $this->newFriendship();
        }
    }

    public function createdStatus() {
        return $this->hasOne('App\Status', 'created_status_id');
    }

    public function statusComment() {
        return $this->hasOne('App\StatusComment', 'status_comment_id');
    }

    public function statusLike() {
        return $this->hasOne('App\StatusLike', 'status_like_id');
    }

    public function statusCommentLike() {
        return $this->hasOne('App\StatusCommentLike', 'status_comment_like_id');
    }

    public function uploadedPhoto() {
        return $this->hasOne('App\Photo', 'uploaded_photo_id');
    }

    public function changedProfilePicture() {
        return $this->hasOne('App\Photo', 'updated_profile_picture_photo_id');
    }

    public function newFriendship() {
        return $this->hasOne('App\Friendship', 'new_friendship_id');
    }
}
