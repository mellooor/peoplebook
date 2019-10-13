<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\StatusPhoto;
use App\Status;
use Illuminate\Support\Collection;
use Intervention\Image\Image;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class Photo extends Model
{
    public $timestamps = false;

    public function uploader() {
        return $this->belongsTo('App\User', 'uploader_id');
    }

    public function getFullURL() {
        return url('/') . '/storage/' . $this->file_name;
    }

    public function activity() {
        return $this->hasOne('App\Activity', 'uploaded_photo_id', 'id');
    }

    /*
     * Takes an existing active profile picture and profile picture thumbnail and makes them un-active.
     */
    public static function deactivateProfilePicture(Photo $newProfilePic, Photo $newProfilePicThumbnail, int $currentUserID) {
        $previouslyActiveProfilePics = Photo::where(function ($query) use ($newProfilePic, $currentUserID) {
            $query->where('type_id', '=', 5)// Active Profile Picture
            ->where('uploader_id', '=', $currentUserID)
                ->where('id', '!=', $newProfilePic->id);
        })->orWhere(function ($query) use ($newProfilePicThumbnail, $currentUserID) {
            $query->where('type_id', '=', 6)// Active Profile Picture Thumbnail
            ->where('uploader_id', '=', $currentUserID)
                ->where('id', '!=', $newProfilePicThumbnail->id);
        })->get();

        foreach ($previouslyActiveProfilePics as $previouslyActiveProfilePic) {
            if ($previouslyActiveProfilePic->type_id === 5) {
                $previouslyActiveProfilePic->type_id = 2;
                $previouslyActiveProfilePic->save();
            } elseif ($previouslyActiveProfilePic->type_id === 6) {
                $previouslyActiveProfilePic->type_id = 4;
                $previouslyActiveProfilePic->save();
            }
        }

        return $previouslyActiveProfilePics;
    }

    public static function revertDeactivatedProfilePicture(Collection $previouslyActiveProfilePics) {
        foreach ($previouslyActiveProfilePics as $previouslyActiveProfilePic) {
            if ($previouslyActiveProfilePic->type_id === 2) {
                $previouslyActiveProfilePic->type_id = 5;
                $previouslyActiveProfilePic->save();
            } elseif ($previouslyActiveProfilePic->type_id === 4) {
                $previouslyActiveProfilePic->type_id = 6;
                $previouslyActiveProfilePic->save();
            }
        }
    }
}
