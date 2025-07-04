<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     schema="Post",
 *     type="object",
 *     title="Post",
 *     required={"id", "title", "content", "author"},
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="title", type="string", example="My First Post"),
 *     @OA\Property(property="content", type="string", example="This is the body of the post."),
 *     @OA\Property(property="author", type="string", example="Jane Doe"),
 *     @OA\Property(property="published_at", type="string", format="date-time", example="2024-07-01T12:00:00Z"),
 *     @OA\Property(property="scheduled_at", type="string", format="date-time", nullable=true, example="2024-07-01T12:00:00Z"),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 */

class PostResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'content' => $this->content,
            'author' => $this->author,
            'scheduled_at' => optional($this->scheduled_at)->toDateTimeString(),
            'published_at' => optional($this->published_at)->toDateTimeString(),
            'created_at' => $this->created_at->toDateTimeString(),
            'updated_at' => $this->updated_at->toDateTimeString(),
        ];
    }
}
