<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class SubscriberFollowing extends Model
{
    use HasFactory;
    protected $guarded=false;
    public function subscriber(){
        return $this->belongsTo(User::class, 'subscriber_id', 'id');
    }
    public function following(){
        return $this->belongsTo(User::class, 'following_id', 'id');
    }

}
