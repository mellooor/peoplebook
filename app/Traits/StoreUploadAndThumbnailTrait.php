<?php
/**
 * Created by PhpStorm.
 * User: Alex
 * Date: 16/07/2019
 * Time: 20:10
 */

namespace App\Traits;
use Intervention\Image\Image as InterventionImage;
use App\Photo;
use Illuminate\Support\Facades\Auth;
use App\User;
use Illuminate\Support\Facades\DB;

trait StoreUploadAndThumbnailTrait
{
    /*
     * Creates 2 DB records in the Photos table for an uploaded image and its related thumbnail.
     *
     * @param InterventionImage $uploadedImage - An Intervention Image instance of the originally uploaded image.
     * @param InterventionImage $thumbnailImage - An Intervention Image instance of the thumbnail that's relative to the originally uploaded image.
     *
     * @return Array - An array containing the Photo instances for the freshly inserted upload image and thumbnail. Returns an empty array if either image cannot be saved to the DB.
     */
    public function storeUploadAndThumbnail(InterventionImage $uploadedImage, InterventionImage $thumbnailImage) {
        $currentUser = User::find(Auth::user()->id);

        $uploadPhoto = new Photo();
        $uploadPhoto->uploader_id = $currentUser->id;
        $uploadPhoto->file_name = $uploadedImage->basename;
        //$uploadPhoto->caption = '';
        $uploadPhoto->type_id = 1;
        $uploadPhoto->time_uploaded = date('Y-m-d H:i:s');
        $uploadPhoto->privacy_type_id = $currentUser->default_photo_privacy_type_id;

        $thumbnailPhoto = new Photo();
        $thumbnailPhoto->uploader_id = $currentUser->id;
        $thumbnailPhoto->file_name = $thumbnailImage->basename;
        $thumbnailPhoto->type_id = 3;
        $thumbnailPhoto->time_uploaded = date('Y-m-d H:i:s');
        $thumbnailPhoto->privacy_type_id = $currentUser->default_photo_privacy_type_id;

        if ($uploadPhoto->save() && $thumbnailPhoto->save()) {
            $array = [
                'uploadPhoto' => $uploadPhoto,
                'thumbnailPhoto' => $thumbnailPhoto
            ];

            return $array;
        } else {
            $uploadPhoto->delete(); // Attempt to delete a record anyway in case 1 out of the 2 photos are inserted correctly
            $thumbnailPhoto->delete(); // Attempt to delete a record anyway in case 1 out of the 2 photos are inserted correctly
            return [];
        }
    }
}