<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\GalleryImageResource;
use App\Models\GalleryImage;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class GalleryController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        return GalleryImageResource::collection(
            GalleryImage::query()
                ->active()
                ->ordered()
                ->get(),
        );
    }
}
