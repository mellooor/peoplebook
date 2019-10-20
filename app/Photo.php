<?php

namespace App;

use App\Traits\FormatDateTrait;
use Illuminate\Database\Eloquent\Model;
use App\StatusPhoto;
use App\Status;
use Illuminate\Support\Collection;
use Intervention\Image\Image;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use App\Library\DateTime as PeopleBookDateTime;

class Photo extends Model
{
    public $timestamps = false;
    protected $dates = ['time_uploaded'];

    const fixedImageAffixes = [ // The set affixes that are to be used in the file naming syntax.
        'original-upload' => '',
        'profile-picture' => '_320x320',
        'thumbnail' => '_260x260',
        'profile-picture-thumbnail' => '_25x25',
        'active-profile-picture' => '_320x320',
        'active-profile-picture-thumbnail' => '_25x25'
    ];

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

    /*
     * Returns the duration up to now since the photo was uploaded in a format set in the Peoplebook
     * DateTime class.
     */
    public function timeUploadedDuration() {
        return PeopleBookDateTime::formatDuration($this->time_uploaded);
    }

    /*
     * Retrieves the base filename for the photo.
     *
     * This returns the base filename (filename of the originally uploaded image) that's relative to the Photo
     * i.e. for a profile picture thumbnail with the filename "foobar_25x25.jpeg", this method returns
     * foobar.jpeg.
     *
     * @return string - The base file name that's relative to the Photo instance. Returns an empty string if the photo type_id isn't recognised.
     */
    public function getBaseFileName() {
        switch($this->type_id) {
            case 1: // Upload
                $baseFileName = $this->file_name;
                break;
            case 2: // Profile Picture
                $regex = '/' . self::fixedImageAffixes['profile-picture'] . '/';
                $baseFileName = preg_replace($regex, '', $this->file_name);
                break;
            case 3: // Thumbnail
                $regex = '/' . self::fixedImageAffixes['thumbnail'] . '/';
                $baseFileName = preg_replace($regex, '', $this->file_name);
                break;
            case 4: // Profile Picture Thumbnail
                $regex = '/' . self::fixedImageAffixes['profile-picture-thumbnail'] . '/';
                $baseFileName = preg_replace($regex, '', $this->file_name);
                break;
            case 5: // Active Profile Picture
                $regex = '/' . self::fixedImageAffixes['active-profile-picture'] . '/';
                $baseFileName = preg_replace($regex, '', $this->file_name);
                break;
            case 6: // Active Profile Picture Thumbnail
                $regex = '/' . self::fixedImageAffixes['active-profile-picture-thumbnail'] . '/';
                $baseFileName = preg_replace($regex, '', $this->file_name);
                break;
            default:
                $baseFileName = "";
        }

        return $baseFileName;
    }

    /*
     * Get an associated photo (thumbnail, profile-picture etc.) for the photo.
     *
     * Takes the base file name for the originally uploaded image and returns it with the relative term affixed.
     *
     * @param string $baseFileName - the base file name for the originally uploaded image.
     * @param string $imageType - The type of image that the user requires the file name for.
     *
     * @return App\Photo - An instance of the Photo class for the associated photo. Returns null if $imageType isn't recognised or associated photo not found.
     */
    public function getAssociatedPhoto(string $imageType) {
        $baseFileName = $this->getBaseFileName();
        $imageTypeArray = array_keys(self::fixedImageAffixes);

        if (in_array($imageType, $imageTypeArray)) {
            $rawFileName = pathinfo($baseFileName, PATHINFO_FILENAME);
            $fileExtension = pathinfo($baseFileName, PATHINFO_EXTENSION);

            $fullFileName = $rawFileName . self::fixedImageAffixes[$imageType] . '.' . $fileExtension;

            return self::where('file_name', '=', $fullFileName)->first();
        } else {
            return null;
        }
    }
}
