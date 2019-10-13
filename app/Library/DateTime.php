<?php
/**
 * Created by PhpStorm.
 * User: Alex
 * Date: 13/10/2019
 * Time: 17:19
 */

namespace App\Library;
use Illuminate\Support\Carbon;

class DateTime
{
    /*
     * Formats a duration for an instance of Carbon that's returned by a model to the format required.
     */
    public static function formatDuration(Carbon $date) {
        if ($date->diffInYears() > 0) {
            return $date->format('d/m/Y') . ' at ' . date('H:i');
        } elseif ($date->diffInDays() > 0) {
            return $date->format('jS F') . ' at ' . date('H:i');
        } elseif ($date->diffInHours() > 0) {
            return 'Today at ' . $date->format('H:i');
        } else {
            return $date->diffForHumans();
        }
    }

    public static function formatDateOfBirth(Carbon $date) {
        return $date->format('d/m/Y');
    }
}