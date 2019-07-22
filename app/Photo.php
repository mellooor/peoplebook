<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\StatusPhoto;
use App\Status;
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
}
