<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Traits\ImagePathsTrait;
use App\Traits\ImageUploadTrait;
use Illuminate\Support\Facades\Auth;
use App\Photo;
use Illuminate\Support\Facades\DB;
use App\Traits\StoreUploadAndThumbnailTrait;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class PhotosController extends Controller
{
    use ImagePathsTrait;
    use ImageUploadTrait;
    use StoreUploadAndThumbnailTrait;

    /**
     * Display all of a user's photos.
     *
     * @param int $id - The ID of the user whose photos will be retrieved.
     * @return \Illuminate\Http\Response - The HTTP response that correlates to the photos being retrieved successfully.
     */
    public function index($id = null)
    {
        if ($user = User::getFromRouteParameter($id)) {
            return view('photos')->with('user', $user);
        } else {
            return redirect()->route('home');
        }
    }

    /**
     * Uploads and stores a new photo, as well as creating a related thumbnail for it.
     *
     * @param  \Illuminate\Http\Request  $request - The HTTP request.
     * @return \Illuminate\Http\Response - The HTTP response that correlates to the success of uploading and storing the photo in the DB.
     */
    public function store(Request $request)
    {
        $currentUserID = Auth::user()->id;

        $validator = Validator::make($request->all(), [
            'photos.*' => 'required|mimes:jpg,jpeg,png|max:20000'
        ]);

        if (!$validator->fails()) {
            if ($request->hasFile('photos')) {
                foreach ($request->file('photos') as $image) {
                    $uploadedImage = $this->uploadImage($image);
                    $thumbnailImage = $this->createThumbnail($uploadedImage->basename);

                    if (!$this->storeUploadAndThumbnail($uploadedImage, $thumbnailImage)) {
                        return redirect()->back()->with('not-uploaded', 'There was an Error When Uploading Your Photos');
                    }
                }

                return redirect()->back()->with('uploaded', 'Photo(s) Uploaded Successfully');
            }
        } else {
            return redirect()->back()->with('not-uploaded', 'There was an Error When Uploading Your Photos');
        }
    }

    /*
     * Stores a new profile picture, as well as creating a related thumbnail for it.
     *
     * The originally uploaded image is used to be resized to profile picture and profile picture thumbnail dimensions before being saved
     * under separate filenames. If the current user already has an active profile picture and active profile picture thumbnail,
     * these are attempted to be made inactive. If an error occurs during this stage, the new profile picture and thumbnail records are
     * deleted and the uploads removed, in order to prevent the possibility of duplicate active profile pictures and active
     * profile picture thumbnails for a given user in the Photos DB table.
     *
     * @param  \Illuminate\Http\Request  $request - The HTTP request.
     * @return \Illuminate\Http\Response - The HTTP response that correlates to the success of uploading and storing the profile picture in the DB.
     */
    public function storeProfilePicture(Request $request) {
        $currentUserID = Auth::user()->id;

        $request->validate([
            'profile_picture' => 'required|mimes:jpg,jpeg,png|max:20000'
        ]);

        if ($request->hasFile('profile_picture')) {
            $image = $request->file('profile_picture');
            $uploadedImage = $this->uploadImage($image);
            $thumbnailImage = $this->createThumbnail($uploadedImage->basename);
            $profilePic = $this->createProfilePicture($uploadedImage->basename);
            $profilePicThumbnail = $this->createProfilePictureThumbnail($uploadedImage->basename);

            $uploadPhoto = new Photo();
            $uploadPhoto->uploader_id = $currentUserID;
            $uploadPhoto->file_name = $uploadedImage->basename;
            //$uploadPhoto->caption = '';
            $uploadPhoto->type_id = 1; // Upload
            $uploadPhoto->time_uploaded = DB::raw('now()');

            $thumbnailPhoto = new Photo();
            $thumbnailPhoto->uploader_id = $currentUserID;
            $thumbnailPhoto->file_name = $thumbnailImage->basename;
            $thumbnailPhoto->type_id = 3; // Thumbnail
            $thumbnailPhoto->time_uploaded = DB::raw('now()');

            $profilePicPhoto = new Photo();
            $profilePicPhoto->uploader_id = $currentUserID;
            $profilePicPhoto->file_name = $profilePic->basename;
            $profilePicPhoto->type_id = 5; // Active Profile Picture
            $profilePicPhoto->time_uploaded = DB::raw('now()');

            $profilePicThumbnailPhoto = new Photo();
            $profilePicThumbnailPhoto->uploader_id = $currentUserID;
            $profilePicThumbnailPhoto->file_name = $profilePicThumbnail->basename;
            $profilePicThumbnailPhoto->type_id = 6; // Active Profile Picture Thumbnail
            $profilePicThumbnailPhoto->time_uploaded = DB::raw('now()');

            try {
                if ($uploadPhoto->save() && $thumbnailPhoto->save() && $profilePicPhoto->save() && $profilePicThumbnailPhoto->save()) {
                    /*
                     * If a profile picture or profile picture thumbnail was previously set for the current user,
                     * update the photo type ID of the photos to a regular profile picture and profile picture thumbnail.
                     */
                    $previouslyActiveProfilePics = Photo::where(function($query) use ($profilePicPhoto, $currentUserID) {
                        $query->where('type_id', '=', 5) // Active Profile Picture
                            ->where('uploader_id', '=', $currentUserID)
                            ->where('id', '!=', $profilePicPhoto->id);
                    })->orWhere(function($query) use ($profilePicThumbnailPhoto, $currentUserID) {
                        $query->where('type_id', '=', 6) // Active Profile Picture Thumbnail
                            ->where('uploader_id', '=', $currentUserID)
                            ->where('id', '!=', $profilePicThumbnailPhoto->id);
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

                    return redirect()->back()->with('created', 'Your Profile Picture has Been Updated');
                } else { // If one of the photos isn't inserted correctly...
                    $uploadPhoto->delete(); // Attempt to delete a record anyway in case 1 out of the 4 photos are inserted correctly
                    $thumbnailPhoto->delete(); // Attempt to delete the record anyway in case 1 out of the 4 photos are inserted correctly
                    $profilePicPhoto->delete(); // Attempt to delete the record anyway in case 1 out of the 4 photos are inserted correctly
                    $profilePicThumbnailPhoto->delete(); // Attempt to delete the record anyway in case 1 out of the 4 photos are inserted correctly
                    return redirect()->back()->with('not-created', 'An Error Occurred when Updating your Profile Picture');
                }
            } catch (\Exception $e) {
                $uploadPhoto->delete(); // Attempt to delete a record anyway in case 1 out of the 4 photos are inserted correctly
                $thumbnailPhoto->delete(); // Attempt to delete the record anyway in case 1 out of the 4 photos are inserted correctly
                $profilePicPhoto->delete(); // Attempt to delete the record anyway in case 1 out of the 4 photos are inserted correctly
                $profilePicThumbnailPhoto->delete(); // Attempt to delete the record anyway in case 1 out of the 4 photos are inserted correctly
                return redirect()->back()->with('not-created', $e);
            }
        }
    }

    /*
     * Stores a new profile picture, based on an existing photo that belongs to the current user in the Photos DB table.
     *
     * Verifies that the basic photo and thumbnail exist first before a check is performed to see if the photo and thumbnail
     * have previously been saved in storage and stored in the Photos DB table as a profile picture and profile picture thumbnail.
     * If they have, then the related DB records have their type_id values updated accordingly and the previously active profile picture/
     * thumbnail are made inactive. Otherwise, the originally uploaded image is used to be resized to profile picture and profile
     * picture thumbnail dimensions before the images are saved under separate filenames.
     *
     * @param \Illuminate\Http\Request $request - The HTTP request.
     * @return \Illuminate\HTTP\Response - The HTTP response that correlates to the success of storing the new profile picture from the existing photo.
     */
    public function changeProfilePicture(Request $request) {
        $request->validate([
            'thumbnail-id' => 'required|integer'
        ]);

        // Get Photo From ID and check that it exists.
        $thumbnail = Photo::find($request->input('thumbnail-id'));

        if ($thumbnail) {
            $baseFileName = $this->getBaseFileName($thumbnail);

            // Search for the related thumbnail and store it in a variable.
            $uploadFile = Photo::where('file_name', '=', $baseFileName);

            if ($uploadFile) {
                $currentUserID = Auth::user()->id;

                // Check to see if the current image already has a profile picture/profile picture thumbnail uploaded + stored in the DB.
                $profilePicFilePath = $this->getAssociatedImagePath($baseFileName, 'profile-picture');
                $profilePicThumbnailFilePath = $this->getAssociatedImagePath($baseFileName, 'profile-picture-thumbnail');
                $activeProfilePicFilePath = $this->getAssociatedImagePath($baseFileName, 'active-profile-picture');
                $activeProfilePicThumbnailFilePath = $this->getAssociatedImagePath($baseFileName, 'active-profile-picture-thumbnail');

                $profilePic = Photo::where('file_name', '=', $profilePicFilePath)->first();
                $profilePicThumbnail = Photo::where('file_name', '=', $profilePicThumbnailFilePath)->first();
                $activeProfilePic = Photo::where('file_name', '=', $activeProfilePicFilePath)->first();
                $activeProfilePicThumbnail = Photo::where('file_name', '=', $activeProfilePicThumbnailFilePath)->first();

                $alreadyIsAProfilePic = ($profilePic && $profilePicThumbnail);
                $alreadyIsActiveProfilePic = ($activeProfilePic && $activeProfilePicThumbnail);

                if (!$alreadyIsAProfilePic && !$alreadyIsActiveProfilePic) {
                    // Resize upload image and thumbnail to profile picture and profile picture thumbnail dimensions + upload.
                    $profilePic = $this->createProfilePicture($baseFileName);
                    $profilePicThumbnail = $this->createProfilePictureThumbnail($baseFileName);

                    // Insert 2 new records into the Photos DB table: 1 for the active-profile-picture and 1 for the active-profile-picture-thumbnail
                    $newProfilePic = new Photo();
                    $newProfilePic->uploader_id = $currentUserID;
                    $newProfilePic->file_name = $profilePic->basename;
                    $newProfilePic->type_id = 5;
                    $newProfilePic->time_uploaded = DB::raw('now()');

                    $newProfilePicThumbnail = new Photo();
                    $newProfilePicThumbnail->uploader_id = $currentUserID;
                    $newProfilePicThumbnail->file_name = $profilePicThumbnail->basename;
                    $newProfilePicThumbnail->type_id = 6;
                    $newProfilePicThumbnail->time_uploaded = DB::raw('now()');

                    if ($newProfilePic->save() && $newProfilePicThumbnail->save()) {
                        // Update the previous active profile picture and profile picture thumbnail to inactive if they were set.
                        try {
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

                            return redirect()->back()->with('updated', 'Your Profile Picture has been Updated');
                        } catch (\Exception $e) {
                            /*
                             * If an error occurs when the previous profile picture and thumbnail are being made inactive, delete the new profile picture and new
                             * profile picture thumbnail in order to prevent any duplicate records of active profile pictures and active profile picture thumbnails
                             * for a user in the Photos DB table.
                             */
                            Storage::delete('public/' . $newProfilePic->file_name);
                            Storage::delete('public/' . $newProfilePicThumbnail->file_name);
                            $newProfilePic->delete();
                            $newProfilePicThumbnail->delete();

                            return redirect()->back()->with('not-updated', $e->getMessage());
                        }
                    }
                } elseif ($alreadyIsAProfilePic) {
                    // Update Photos table profile pic records to active profile picture and active profile picture thumbnail
                    $profilePic->type_id = 5;
                    $profilePicThumbnail->type_id = 6;

                    if ($profilePic->save() && $profilePicThumbnail->save()) {
                        // Update the previous active profile picture and profile picture thumbnail to inactive if they were set.
                        try {
                            $previouslyActiveProfilePics = Photo::where(function ($query) use ($profilePic, $currentUserID) {
                                $query->where('type_id', '=', 5)// Active Profile Picture
                                ->where('uploader_id', '=', $currentUserID)
                                    ->where('id', '!=', $profilePic->id);
                            })->orWhere(function ($query) use ($profilePicThumbnail, $currentUserID) {
                                $query->where('type_id', '=', 6)// Active Profile Picture Thumbnail
                                ->where('uploader_id', '=', $currentUserID)
                                    ->where('id', '!=', $profilePicThumbnail->id);
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

                            return redirect()->back()->with('updated', 'Your Profile Picture has been Updated');
                        } catch (\Exception $e) {
                            /*
                             * If an error occurs when the previous profile picture and thumbnail are being made inactive, set the new profile picture and new
                             * profile picture thumbnail type values back to an inactive profile picture/profile picture thumbnail in order to prevent any duplicate
                             * records of active profile pictures and active profile picture thumbnails for a user in the Photos DB table.
                             */
                            $profilePic->type_id = 2;
                            $profilePicThumbnail->type_id = 4;
                            $profilePic->save();
                            $profilePicThumbnail->save();

                            return redirect()->back()->with('not-updated', $e->getMessage());
                        }
                    }
                } elseif ($alreadyIsActiveProfilePic) {
                    return redirect()->back()->with('not-updated', 'This Image is Already your Active Profile Picture');
                }
            }
        }
    }

    /**
     * Remove the specified Photo from storage, along with any related photos (i.e. thumbnails, profile picture etc.).
     *
     * Validates the input and verifies that the photo from the request exists before retrieving all related photos,
     * such as the thumbnail, profile picture etc. and deleting each one individually from storage before removing them
     * from the Photos DB table, depending on whether they were successfully deleted.
     *
     * @param  \Illuminate\Http\Request $request - The HTTP request.
     * @return \Illuminate\Http\Response - The HTTP request that correlates to the success of deleting the photo and all related photos.
     */
    public function destroy(Request $request)
    {
        $request->validate([
            'thumbnail-photo-ID' => 'required|integer'
        ]);

        $thumbnailPhotoID = $request->input('thumbnail-photo-ID');

        // Get Photo From ID and check that it exists.
        $thumbnail = Photo::find($thumbnailPhotoID);

        if ($thumbnail) {
            $baseFileName = $this->getBaseFileName($thumbnail);

            // Search for any related photos (thumbnails, profile pictures, uploads) and store them in variables.
            $uploadFileName = $baseFileName;
            $thumbnailFileName = $thumbnail->file_name;
            $profilePicFileName = $this->getAssociatedImagePath($baseFileName, 'profile-picture');
            $profilePicThumbnailFileName = $this->getAssociatedImagePath($baseFileName, 'profile-picture-thumbnail');
            $activeProfilePicFileName = $this->getAssociatedImagePath($baseFileName, 'active-profile-picture');
            $activeProfilePicThumbnailFilename = $this->getAssociatedImagePath($baseFileName, 'active_profile-picture-thumbnail');

            $upload = Photo::where('file_name', '=', $uploadFileName)->first();
            $profilePic = Photo::where('file_name', '=', $profilePicFileName)->first();
            $profilePicThumbnail = Photo::where('file_name', '=', $profilePicThumbnailFileName)->first();
            $activeProfilePic = Photo::where('file_name', '=', $activeProfilePicFileName)->first();
            $activeProfilePicThumbnail = Photo::where('file_name', '=', $activeProfilePicThumbnailFilename)->first();

            // Delete the photos from storage if the filename exists.
            $uploadDeleted = Storage::delete('public/' . $uploadFileName);
            $thumbnailDeleted = Storage::delete('public/' . $thumbnailFileName);
            $profilePicDeleted = Storage::delete('public/' . $profilePicFileName);
            $profilePicThumbnailDeleted = Storage::delete('public/' . $profilePicThumbnailFileName);
            $activeProfilePicDeleted = Storage::delete('public/' . $activeProfilePicFileName);
            $activeProfilePicThumbnailDeleted = Storage::delete('public/' . $activeProfilePicThumbnailFilename);

            // Remove each photo that was deleted from the DB.
            if ($uploadDeleted) {
                $upload->delete();
            }
            if ($thumbnailDeleted) {
                $thumbnail->delete();
            }
            if ($profilePicDeleted) {
                $profilePic->delete();
            }
            if ($profilePicThumbnailDeleted) {
                $profilePicThumbnail->delete();
            }
            if ($activeProfilePicDeleted) {
                $activeProfilePic->delete();
            }
            if ($activeProfilePicThumbnailDeleted) {
                $activeProfilePicThumbnail->delete();
            }

            return redirect()->back()->with('deleted', 'Photo deleted');
        } else {
            return redirect()->back()->with('not-deleted', 'An Error Occurred when Deleting the Photo');
        }
    }
}