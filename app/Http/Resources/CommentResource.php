<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CommentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $user=isset($this->parent) ? $this->parent->user : null;
        return [
            'id'=>$this->id,
            'body'=>$this->body,
            'date'=>$this->date,
            'user'=>new UserResource($this->user),
            'answered_for_user'=>$user
        ];
    }
}
