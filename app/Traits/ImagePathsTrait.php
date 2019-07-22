<?php
/**
 * Created by PhpStorm.
 * User: Alex
 * Date: 20/07/2019
 * Time: 12:54
 */

namespace App\Traits;
use Intervention\Image\Image as InterventionImage;
use Illuminate\Http\UploadedFile;
use App\Photo;

trait ImagePathsTrait
{
    public $fixedImageAffixes = [ // The set affixes that are to be used in the file naming syntax.
        'profile-picture' => '_320x320',
        'thumbnail' => '_260x260',
        'profile-picture-thumbnail' => '_25x25',
        'active-profile-picture' => '_320x320',
        'active-profile-picture-thumbnail' => '_25x25'
    ];

    /*
     * Get an associated image (thumbnail, profile-picture etc.) name from the base file name.
     *
     * Takes the base file name for the originally uploaded image and returns it with the relative term affixed.
     *
     * @param string $baseFileName - the base file name for the originally uploaded image.
     * @param string $imageType - The type of image that the user requires the file name for.
     *
     * @return string - The file name for the required image type. Returns an empty string if the $imageType parameter isn't recognised.
     */
    public function getAssociatedImagePath(string $baseFileName, string $imageType) {
        $imageTypeArray = array_keys($this->fixedImageAffixes);

        if (in_array($imageType, $imageTypeArray)) {
            $rawFileName = pathinfo($baseFileName, PATHINFO_FILENAME);
            $fileExtension = pathinfo($baseFileName, PATHINFO_EXTENSION);

            return $rawFileName . $this->fixedImageAffixes[$imageType] . '.' . $fileExtension;
        } else {
            return "";
        }
    }

    /*
     * Retrieves the base filename for an instance of the Photo class.
     *
     * This returns the base filename (filename of the originally uploaded image) that's relative to the Photo instance
     * provided. i.e. for a profile picture thumbnail with the filename "foobar_25x25.jpeg", this method returns
     * foobar.jpeg.
     *
     * @param Photo $photo - The instance of the Photo whose relative base filename is required.
     *
     * @return string - The base file name that's relative to the Photo instance. Returns an empty string if the photo type_id isn't recognised.
     */
    public function getBaseFileName(Photo $photo) {
        switch($photo->type_id) {
            case 1: // Upload
                $baseFileName = $photo->file_name;
                break;
            case 2: // Profile Picture
                $regex = '/' . $this->fixedImageAffixes['profile-picture'] . '/';
                $baseFileName = preg_replace($regex, '', $photo->file_name);
                break;
            case 3: // Thumbnail
                $regex = '/' . $this->fixedImageAffixes['thumbnail'] . '/';
                $baseFileName = preg_replace($regex, '', $photo->file_name);
                break;
            case 4: // Profile Picture Thumbnail
                $regex = '/' . $this->fixedImageAffixes['profile-picture-thumbnail'] . '/';
                $baseFileName = preg_replace($regex, '', $photo->file_name);
                break;
            case 5: // Active Profile Picture
                $regex = '/' . $this->fixedImageAffixes['active-profile-picture'] . '/';
                $baseFileName = preg_replace($regex, '', $photo->file_name);
                break;
            case 6: // Active Profile Picture Thumbnail
                $regex = '/' . $this->fixedImageAffixes['active-profile-picture-thumbnail'] . '/';
                $baseFileName = preg_replace($regex, '', $photo->file_name);
                break;
            default:
                $baseFileName = "";
        }

        return $baseFileName;
    }

    /*
     * Creates an image path to be stored in the Photos DB table based on the base filename which matches the chosen image type.
     * I.e. the profile picture file path for "foobar.jpeg" would be "storage/foobar_320x320.jpeg".
     *
     * @param string $basename - The base filename (the filename for the originally uploaded image).
     * @param string $imageType - The type of the image that the user requires the image path for.
     *
     * @return string - The file path for the type of image chosen by the user.
     */
    public function createImagePath(string $basename, string $imageType) {
        $imageTypeArray = array_keys($this->fixedImageAffixes);

        if (in_array($imageType, $imageTypeArray)) {
            $imageFileNameWithoutExtension = pathinfo($basename, PATHINFO_FILENAME);
            $imageFileExtension = pathinfo($basename, PATHINFO_EXTENSION);

            return 'storage/' . $imageFileNameWithoutExtension . $this->fixedImageAffixes[$imageType] . '.' . $imageFileExtension;
        }
    }
}