<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VideoModel extends Model
{
    use HasFactory, HasUuids;

    protected $fillable =[
        "user_id",
        "blur_hash",
        "caption",
        "description",
        "thumbnail",
        "video_url"
    ];
}
