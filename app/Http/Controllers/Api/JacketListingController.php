<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\JacketListingResource;
use App\Models\JacketListing;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class JacketListingController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        return JacketListingResource::collection(
            JacketListing::query()
                ->published()
                ->ordered()
                ->get(),
        );
    }

    public function show(string $value): JacketListingResource
    {
        $jacketListing = JacketListing::query()
            ->published()
            ->whereKey($value)
            ->first();

        if ($jacketListing === null) {
            throw new NotFoundHttpException;
        }

        return new JacketListingResource($jacketListing);
    }
}
