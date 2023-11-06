<?php

namespace App\Http\Controllers;

use App\Http\Requests\CommentRequest;
use App\Http\Requests\CommentRequset;
use App\Http\Requests\PostRequest;
use App\Http\Requests\RepostRequest;
use App\Http\Resources\CommentResource;
use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\PostImage;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\PostrResource;
use App\Models\Comment;
use App\Models\LikedPost;
use App\Models\User;

class PostController extends Controller
{
    public function getPosts()
    {
        $posts = Post::where('user_id', auth()->id())->withCount('reposts')->latest()->get();
        $liked = LikedPost::where('user_id', auth()->id())
            ->get('post_id')
            ->pluck('post_id')
            ->toArray();
        foreach ($posts as $post) {
            if (in_array($post->id, $liked)) {
                $post->is_liked = true;
            }
        }
        return PostrResource::collection($posts);
    }
    public function store(PostRequest $request)
    {
        try {
            DB::beginTransaction();
            $data = $request->validated();
            $imageId = $data['image_id'];
            unset($data['image_id']);
            $data['user_id'] = auth()->id();

            $post = Post::create($data);
            $this->proccessImage($post, $imageId);
            PostImage::clearImages();
            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();
            return response()->json(['error' => $exception->getMessage()]);
        }
        return new PostrResource($post);
    }
    private function proccessImage($post, $imageId)
    {
        if (isset($imageId)) {
            $image = PostImage::find($imageId);
            $image->update([
                'status' => true,
                'post_id' => $post->id
            ]);
        }
    }
    public function toggleLike(Post $post)
    {

        $liked = User::find(auth()->id())->liked()->toggle($post->id);
        $data['is_liked'] = count($liked['attached']) > 0 ? true : false;
        $data['likes_count'] = $post->likedUsers->count();
        return $data;
    }
    public function repost(RepostRequest $request, Post $post)
    {
        $data = $request->validated();
        $data['user_id'] = auth()->id();
        $data['reposted_id'] = $post->id;
        Post::create($data);
    }
    public function comment(Post $post, CommentRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = auth()->id();
        $data['post_id'] = $post->id;
        $comment = Comment::create($data);
        return new CommentResource($comment);
    }
    public function getComments(Post $post){
     return CommentResource::collection($post->comments);
    }
}
