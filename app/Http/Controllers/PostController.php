<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostRequest;
use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\PostImage;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\PostrResource;

class PostController extends Controller
{
    public function __invoke(PostRequest $request)
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
        }
         catch (\Exception $exception)
          {
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
}
