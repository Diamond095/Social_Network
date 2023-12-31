<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PostrResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $url = isset($this->image) ? $this->image->url : null;
        return [
            'id' => $this->id,
            'title' => $this->title,
            'content' => $this->content,
            'image_url' => $url,
            'date' => $this->date,
            'is_liked' => $this->is_liked ?? false,
            'likes_count' => $this->likedUsers->count(),
            'reposted_post' => new RepostResource($this->reposted),
            'comments_count'=>$this->comments->count(),
            'reposts_count'=>$this->reposts_count,
            'user'=>new UserResource($this->user)
        ];
    }
}
