<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UploadFile extends Model
{
    protected $fillable = [
        'name',
        'upload_file_status_id'
    ];
}
