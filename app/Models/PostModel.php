<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PostModel extends Model
{
    use HasFactory ,HasUuids;

    protected $fillable = [
        "id",
        "type",
        "user_id",
        "blur_hash",
        "content",
        "description",
        "thumbnail",
        "post_url"

    ];

    public function likes()
    {
        return $this->hasMany(Like::class, 'post_id');
    }

    public function comments()
    {
        return $this->hasMany(CommentModel::class, 'post_id');
    }

}
