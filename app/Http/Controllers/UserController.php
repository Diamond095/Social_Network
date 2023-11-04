<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Resources\PostrResource;
use App\Models\SubscriberFollowing;

class UserController extends Controller
{
    public function getUsers()
    {
        $users = User::whereNot('id', auth()->id())->get();
        $followings=SubscriberFollowing::where('subscriber_id', auth()->id())
        ->get('following_id')
        ->pluck('following_id')
        ->toArray();
        foreach($users as $user){
            if(in_array($user->id, $followings)){
                $user->is_following=true;
            }
            }
        
        return UserResource::collection($users);
    }

    public function showPostOfUser(User $user)
    {
        return PostrResource::collection($user->posts);
    }
    public function toggleFollowing(User $user)
    {
       $authUser=User::find(auth()->id());
       $res=$authUser->followings()->toggle($user->id);
       $data['is_following']=count($res['attached'])>0 ? true : false;
       return $data;
      
    }
}
