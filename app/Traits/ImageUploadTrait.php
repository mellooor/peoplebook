<?php

namespace App\Traits;


use Illuminate\Http\UploadedFile;
use Intervention\Image\Image as InterventionImage;
use Intervention\Image\Facades\Image as InterventionImageFacade;

trait ImageUploadTrait
{
    /*
     * Process and store a freshly uploaded file from a user.
     *
     * Stores a fresh upload in the "public" subfolder of the Laravel "storage" directory, before creating an
     * Intervention Image object for the file and resizing the image to meet set max-width and max-height dimensions,
     * depending on the aspect ratio of the raw upload image.
     *
     * @param UploadedFile $image - the freshly uploaded image.
     *
     * @return Intervention\Image\Image - an Intervention Image instance of the uploaded photo.
     */
    public function uploadImage(UploadedFile $image) {
        // Upload Image
        $image->store('public');
        $imageFileName = $image->hashName();
        $imagePublicFileURL = 'storage/' . $imageFileName;

        $image = InterventionImageFacade::make($imagePublicFileURL);

        // Shrink image to a max-width/max-height for the largest browser screens (but maintain aspect ratio).
        $aspectRatio = $this->getAspectRatio($image);

        // 500 x 498

        if ($aspectRatio > 1) { // Aspect Ratio > 1 - set max-width whilst keeping aspect ratio (1920px).
            if ($image->width() >= 1920) { // Only resize image of this aspect ratio if its width exceeds the max-width.
                $image->resize(1920, null, function($constraint) {
                    $constraint->aspectRatio();
                });
            }
        } elseif ($aspectRatio === 1) { // Aspect Ratio === 1 - set either max-width or max-height whilst keeping aspect ratio.
            if ($image->height() >= 1080) { // Only resize image of this aspect ratio if its height exceeds the max-height.
                $image->resize(null, 1080, function($constraint) {
                    $constraint->aspectRatio();
                });
            }
        } elseif ($aspectRatio < 1) { // Aspect Ratio < 1 - set max-height whilst keeping aspect ratio (1080px).
            if ($image->height() >= 1080) { // Only resize image of this aspect ratio if its height exceeds the max-height.
                $image->resize(null, 1080, function($constraint) {
                    $constraint->aspectRatio();
                });
            }
        }

        $image->save(); // Overwrite the originally uploaded image.

        return $image;
    }

    /*
     * Resizes the main uploaded image to thumbnail dimensions and stores it under a different filename.
     *
     * Thumbnail dimensions are 260x260px. If the height or width of the main uploaded image is less than either of these,
     * then the canvas is resized to 260x260px and the image is centered. Otherwise, the image is cropped centrally and
     * resized to the relevant dimensions. The filename syntax is the same as the originally uploaded file with an
     * affix of _260x260.
     *
     * @param string $imageBaseName - the filename of the originally uploaded image.
     *
     * @return Intervention\Image\Image - An Intervention Image instance of the thumbnail.
     */
    public function createThumbnail(string $imageBaseName) {
        $thumbnailImgPath = $this->createImagePath($imageBaseName, 'thumbnail');
        $imagePublicURI = 'storage/' . $imageBaseName;

        $thumbnail = InterventionImageFacade::make($imagePublicURI);

        /*
         * Reduce the size of the image to 260x260px and crop centrally if the aspect ratio is higher or lower than 1 so that the image doesn't stretch
         * If the height or width of the image is less than 260px, resize the canvas to 260x260px and center the image.
         */
        if ($thumbnail->width() < 260 || $thumbnail->height() < 260) {
            $thumbnail->resizeCanvas(260, 260);
        } else {
            $thumbnail->fit(260); // Resize image to 260x260 and crop centrally
        }

        $thumbnail->save($thumbnailImgPath);

        return $thumbnail;
    }

    /*
     * Resizes the main uploaded image to profile picture dimensions and stores it under a different filename.
     *
     * Profile picture dimensions are 320x320px. If the height or width of the main uploaded image is less than either of these,
     * then the canvas is resized to 320x320px and the image is centered. Otherwise, the image is cropped centrally and
     * resized to the relevant dimensions. The filename syntax is the same as the originally uploaded file with an
     * affix of _320x320.
     *
     * @param string $imageBaseName - the filename of the originally uploaded image.
     *
     * @return Intervention\Image\Image - An Intervention Image instance of the profile picture.
     */
    public function createProfilePicture(string $imageBasename) {
        $ProfilePicImgPath = $this->createImagePath($imageBasename, 'profile-picture');
        $imagePublicURI = 'storage/' . $imageBasename;

        $profilePic = InterventionImageFacade::make($imagePublicURI);

        /*
         * Reduce the size of the image to 320x320px and crop centrally if the aspect ratio is higher or lower than 1 so that the image doesn't stretch
         * If the height or width of the image is less than 320px, resize the canvas to 320x320px and center the image.
         */
        if ($profilePic->width() < 320 || $profilePic->height() < 320) {
            $profilePic->resizeCanvas(320, 320);
        } else {
            $profilePic->fit(320); // Resize image to 260x260 and crop centrally
        }

        $profilePic->save($ProfilePicImgPath);

        return $profilePic;
    }

    /*
     * Resizes the main uploaded image to profile picture thumbnail dimensions and stores it under a different filename.
     *
     * Profile picture thumbnail dimensions are 25x25px. If the height or width of the main uploaded image is less than either of these,
     * then the canvas is resized to 25x25px and the image is centered. Otherwise, the image is cropped centrally and
     * resized to the relevant dimensions. The filename syntax is the same as the originally uploaded file with an
     * affix of _25x25.
     *
     * @param string $imageBaseName - the filename of the originally uploaded image.
     *
     * @return Intervention\Image\Image - An Intervention Image instance of the profile picture thumbnail.
     */
    public function createProfilePictureThumbnail(string $imageBasename) {
        $ProfilePicThumbnailPath = $this->createImagePath($imageBasename, 'profile-picture-thumbnail');
        $imagePublicURI = 'storage/' . $imageBasename;

        $profilePicThumbnail = InterventionImageFacade::make($imagePublicURI);

        /*
         * Reduce the size of the image to 25x25px and crop centrally if the aspect ratio is higher or lower than 1 so that the image doesn't stretch
         * If the height or width of the image is less than 25px, resize the canvas to 25x25px and center the image.
         */
        if ($profilePicThumbnail->width() < 25 || $profilePicThumbnail->height() < 25) {
            $profilePicThumbnail->resizeCanvas(25, 25);
        } else {
            $profilePicThumbnail->fit(25); // Resize image to 25x25 and crop centrally
        }

        $profilePicThumbnail->save($ProfilePicThumbnailPath);

        return $profilePicThumbnail;
    }

    /*
     * Returns the aspect ratio of an Intervention Image instance.
     *
     * @param Intervention\Image\Image - An instance of the Intervention Image class.
     *
     * @return int - The aspect ratio of the Intervention Image instance.
     */
    public function getAspectRatio(InterventionImage $image) {
        $imageXSize = $image->width();
        $imageYSize = $image->height();

        return $imageXSize / $imageYSize;
    }
}