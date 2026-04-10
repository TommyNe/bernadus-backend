<?php

namespace App\Actions;

use App\Http\Resources\PageContentResource;
use App\Models\ExternalLink;
use App\Models\Flyer;
use App\Models\MembershipDocument;
use App\Models\Page;
use App\Models\PageSection;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ResolvePageContent
{
    /**
     * @return array<int, string>
     */
    protected function extractSectionLinkKeys(Page $page): array
    {
        return $page->sections
            ->flatMap(function (PageSection $section): array {
                $data = is_array($section->data) ? $section->data : [];
                $keys = [];

                if (is_string($data['link_key'] ?? null) && filled($data['link_key'])) {
                    $keys[] = $data['link_key'];
                }

                foreach ($data['items'] ?? [] as $item) {
                    if (! is_array($item)) {
                        continue;
                    }

                    if (is_string($item['link_key'] ?? null) && filled($item['link_key'])) {
                        $keys[] = $item['link_key'];
                    }
                }

                return $keys;
            })
            ->unique()
            ->values()
            ->all();
    }

    public function handle(string $slug): PageContentResource
    {
        $page = Page::query()
            ->with('sections')
            ->where('slug', $slug)
            ->first();

        if ($page === null) {
            throw new NotFoundHttpException;
        }

        $linkKeys = [
            sprintf('page.%s.flyer', $slug),
            sprintf('page.%s.contact', $slug),
            'official_flyer',
            'official_contact',
            ...$this->extractSectionLinkKeys($page),
        ];

        $links = ExternalLink::query()
            ->whereIn('link_key', array_values(array_unique($linkKeys)))
            ->get()
            ->keyBy('link_key');

        return new PageContentResource([
            'page' => $page,
            'links' => $links,
            'applicationDocument' => MembershipDocument::query()->current('application')->first(),
            'flyerDocument' => Flyer::query()->current()->first(),
        ]);
    }
}
