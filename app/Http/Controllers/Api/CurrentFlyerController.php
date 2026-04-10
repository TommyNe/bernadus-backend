<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CurrentFlyerResource;
use App\Models\Flyer;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CurrentFlyerController extends Controller
{
    public function __invoke(): CurrentFlyerResource
    {
        $flyer = Flyer::query()->current()->first();

        if ($flyer === null) {
            throw new NotFoundHttpException('Aktuell ist noch kein Flyer hinterlegt.');
        }

        return new CurrentFlyerResource($flyer);
    }
}
