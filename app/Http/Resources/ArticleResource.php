<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ArticleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $upvoted = $this->reputation()->where('is_upvote', true)->count();
        $downvoted= $this->reputation()->where('is_upvote', false)->count();
        return [
            'id' => $this->id,
            'title' => $this->title,
            'text' => $this->text,
            'user' => new UserResource($this->user),
            'reputation' => $upvoted - $downvoted,
        ];
    }
}
