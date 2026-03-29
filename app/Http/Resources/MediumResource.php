<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MediumResource extends JsonResource
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
            'disk' => $this->disk,
            'path' => $this->path,
            'url' => $this->path ? Storage::disk($this->disk)->url($this->path) : null,
            'filename' => $this->filename,
            'mime_type' => $this->mime_type,
            'extension' => $this->extension,
            'size' => $this->size,
            'width' => $this->width,
            'height' => $this->height,
            'title' => $this->title,
            'alt_text' => $this->alt_text,
            'portraits' => PersonResource::collection($this->whenLoaded('portraits')),
            'chronicle_images' => ChronicleEntryResource::collection($this->whenLoaded('chronicleImages')),
            ...$this->timestamps($request),
        ];
    }
}
