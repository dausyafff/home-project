<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class ProjectResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'          => $this->id,
            'title'       => $this->title,
            'slug'        => $this->slug,
            'description' => $this->description,
            'tech_stack'  => $this->tech_stack,
            'github_url'  => $this->github_url,
            'live_url'    => $this->live_url,
            'thumbnail'   => $this->thumbnail
                ? Storage::disk('public')->url($this->thumbnail) : null,
            'status'      => $this->status,
            'is_featured' => $this->is_featured,
            'created_at'  => $this->created_at->toDateString(),
        ];
    }
}
