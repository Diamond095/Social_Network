<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Resources\PostrResource;
use App\Models\LikedPost;
use App\Models\SubscriberFollowing;
use App\Models\Post;
use App\Http\Requests\StatRequest;

class UserController extends Controller
{
    public function getUsers()
    {
        $users = User::whereNot('id', auth()->id())->get();
        $followings = SubscriberFollowing::where('subscriber_id', auth()->id())
            ->get('following_id')
            ->pluck('following_id')
            ->toArray();
        foreach ($users as $user) {
            if (in_array($user->id, $followings)) {
                $user->is_following = true;
            }
        }

        return UserResource::collection($users);
    }

    public function showPostOfUser(User $user)
    {
        $posts = $user->posts()->withCount('reposts')->latest()->get();
        $postsId = [];
        foreach ($posts as $post) {
            $postsId[] = $post->id;
        }
        $likedPosts = LikedPost::where('user_id', auth()->id())
            ->whereIn('post_id', $postsId)
            ->get('post_id')
            ->pluck('post_id')
            ->toArray();

        foreach ($posts as $post) {
            if (in_array($post->id, $likedPosts)) {
                $post->is_liked = true;
            }
        }
        return PostrResource::collection($posts);
    }
    public function toggleFollowing(User $user)
    {
        $authUser = User::find(auth()->id());
        $res = $authUser->followings()->toggle($user->id);
        $data['is_following'] = count($res['attached']) > 0 ? true : false;

        return $data;
    }
    public function feed()
    {
        $followings = User::find(auth()->id())->followings->pluck('id')->toArray();
        $likedPosts = LikedPost::where('user_id', auth()->id())
            ->get('post_id')
            ->pluck('post_id')
            ->toArray();
        $posts = Post::whereIn('user_id', $followings)
            ->whereNotIn('id', $likedPosts)
            ->withCount('reposts')
            ->latest()
            ->get();
        foreach ($posts as $post) {
            if (in_array($post->id, $likedPosts)) {
                $post->is_liked = true;
            }
        }
        return PostrResource::collection($posts);
    }
    public function stat(StatRequest $request)
    {
        $data = $request->validated();
        $userId= isset($data['user_id']) ? $data['user_id'] : auth()->id();

        $result=[];
        $result['subscribers_count']=SubscriberFollowing::where('following_id', $userId)->count();
        $result['followings_count']=SubscriberFollowing::where('subscriber_id', $userId)->count();
        $postIds = Post::where('user_id', $userId)->get('id')->pluck('id')->toArray();
        $result['likes_count'] = LikedPost::whereIn('post_id', $postIds)->count();
        $result['posts_count'] = count($postIds);
        return response()->json(['data' => $result]);
    }
}
