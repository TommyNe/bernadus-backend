<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\PageContentResource;
use App\Models\ExternalLink;
use App\Models\Page;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class PageContentController extends ResourceController
{
    public function show(string $slug): PageContentResource
    {
        $page = Page::query()
            ->with('sections')
            ->where('slug', $slug)
            ->first();

        if ($page === null) {
            throw new NotFoundHttpException;
        }

        $links = ExternalLink::query()
            ->whereIn('link_key', [
                sprintf('page.%s.flyer', $slug),
                sprintf('page.%s.contact', $slug),
                'official_flyer',
                'official_contact',
            ])
            ->get()
            ->keyBy('link_key');

        return new PageContentResource([
            'page' => $page,
            'links' => $links,
        ]);
    }
}
