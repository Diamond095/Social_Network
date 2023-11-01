<?php

namespace App\Http\Controllers;

use App\Http\Requests\ImageRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\PostImage;
use App\Http\Resources\PostImageResource;
use PDO;

class PostImageController extends Controller
{
    public function __invoke(ImageRequest $request)
    {
        $path = Storage::disk('public')->put('/images', $request['image']);
        $image = PostImage::create([
            'path' => $path,
            'user_id' => auth()->id()
        ]);
        
        return new PostImageResource($image);
    }
    
}
