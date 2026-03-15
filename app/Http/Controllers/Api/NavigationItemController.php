<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\NavigationItemResource;
use App\Models\NavigationItem;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class NavigationItemController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        $navigationItems = NavigationItem::query()
            ->roots()
            ->with('children')
            ->get();

        return NavigationItemResource::collection($navigationItems);
    }

    public function show(NavigationItem $navigationItem): NavigationItemResource
    {
        $navigationItem->load(['parent', 'children']);

        return new NavigationItemResource($navigationItem);
    }
}
