<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LikedPost extends Model
{
    use HasFactory;
    protected $table = 'liked_posts';
    protected $guarded=false;
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
