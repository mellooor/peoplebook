<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    public $timestamps = false;

    public function activity() {
        return $this->belongsTo('App\Activity', 'activity_id');
    }

    public function notificationType() {
        return $this->belongsTo('App\NotificationType', 'type_id');
    }

    /*
     * Updates the is_active value for a notification to false.
     */
    public function hasBeenSeen() {
        $this->is_active = 0;
        if ($this->save()) {
            return $this;
        } else {
            return false;
        }
    }
}
