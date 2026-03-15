<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\AboutSectionResource;
use App\Http\Resources\ContactEntryResource;
use App\Http\Resources\EventItemResource;
use App\Http\Resources\GalleryHonorResource;
use App\Http\Resources\ParticipationOptionResource;
use App\Http\Resources\ServiceMaterialResource;
use App\Http\Resources\StartPageResource;
use App\Models\AboutSection;
use App\Models\ContactEntry;
use App\Models\EventItem;
use App\Models\GalleryHonor;
use App\Models\ParticipationOption;
use App\Models\ServiceMaterial;
use App\Models\StartPage;
use Illuminate\Http\JsonResponse;

class CategoryContentController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json([
            'data' => [
                'start' => StartPageResource::collection(StartPage::query()->ordered()->get()),
                'about' => AboutSectionResource::collection(AboutSection::query()->ordered()->get()),
                'events' => EventItemResource::collection(EventItem::query()->ordered()->get()),
                'service_materials' => ServiceMaterialResource::collection(ServiceMaterial::query()->ordered()->get()),
                'gallery_honors' => GalleryHonorResource::collection(GalleryHonor::query()->ordered()->get()),
                'participation' => ParticipationOptionResource::collection(ParticipationOption::query()->ordered()->get()),
                'contact' => ContactEntryResource::collection(ContactEntry::query()->ordered()->get()),
            ],
        ]);
    }

    public function show(string $category): JsonResponse
    {
        [$resourceClass, $items] = match ($category) {
            'start' => [StartPageResource::class, StartPage::query()->ordered()->get()],
            'about' => [AboutSectionResource::class, AboutSection::query()->ordered()->get()],
            'events' => [EventItemResource::class, EventItem::query()->ordered()->get()],
            'service-materials' => [ServiceMaterialResource::class, ServiceMaterial::query()->ordered()->get()],
            'gallery-honors' => [GalleryHonorResource::class, GalleryHonor::query()->ordered()->get()],
            'participation' => [ParticipationOptionResource::class, ParticipationOption::query()->ordered()->get()],
            'contact' => [ContactEntryResource::class, ContactEntry::query()->ordered()->get()],
            default => abort(404),
        };

        return response()->json([
            'data' => $resourceClass::collection($items),
        ]);
    }
}
