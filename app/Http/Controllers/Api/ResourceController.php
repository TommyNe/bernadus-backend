<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Resources\Json\JsonResource;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

abstract class ResourceController extends Controller
{
    /**
     * @var class-string<Model>
     */
    protected string $modelClass;

    /**
     * @var class-string<JsonResource>
     */
    protected string $resourceClass;

    /**
     * @var list<string>
     */
    protected array $indexRelationships = [];

    /**
     * @var list<string>
     */
    protected array $showRelationships = [];

    protected string $routeKey = 'id';

    public function index(): AnonymousResourceCollection
    {
        $modelClass = $this->modelClass;

        $query = $modelClass::query()->with($this->indexRelationships);

        $this->applyIndexOrdering($query);

        return $this->resourceClass::collection($query->get());
    }

    public function show(string $value): JsonResource
    {
        $modelClass = $this->modelClass;

        $record = $modelClass::query()
            ->with($this->showRelationships)
            ->where($this->routeKey, $value)
            ->first();

        if ($record === null) {
            throw new NotFoundHttpException;
        }

        return new $this->resourceClass($record);
    }

    protected function applyIndexOrdering(Builder $query): void {}
}
