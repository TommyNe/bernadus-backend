<?php

namespace App\Http\Resources;

use App\Models\ExternalLink;
use App\Models\Page;
use App\Models\PageSection;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class PageContentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        /** @var Page $page */
        $page = $this->resource['page'];
        /** @var Collection<string, ExternalLink> $links */
        $links = $this->resource['links'];
        $sections = $page->sections;

        return [
            'slug' => $page->slug,
            'title' => $page->title,
            'intro' => $this->resolveIntro($sections),
            'flyerUrl' => $this->resolveLinkUrl($links, sprintf('page.%s.flyer', $page->slug), 'official_flyer'),
            'contactUrl' => $this->resolveLinkUrl($links, sprintf('page.%s.contact', $page->slug), 'official_contact'),
            'membershipOffers' => $this->resolveMembershipOffers($sections),
            'practicalNotes' => $this->resolvePracticalNotes($sections),
            'faq' => $this->resolveFaqItems($sections),
            'meta' => [
                'metaTitle' => $page->meta_title,
                'metaDescription' => $page->meta_description,
                'status' => $page->status,
                'publishedAt' => $this->serializeDate($page->published_at),
            ],
        ];
    }

    /**
     * @param  Collection<int, PageSection>  $sections
     */
    protected function resolveIntro(Collection $sections): ?string
    {
        $introSection = $sections
            ->first(fn (PageSection $section): bool => $section->section_key === 'membership-hero')
            ?? $sections->first(fn (PageSection $section): bool => $section->section_type === 'hero' && filled($section->content))
            ?? $sections->first(fn (PageSection $section): bool => $section->section_type === 'rich_text' && filled($section->content));

        return $introSection?->content;
    }

    /**
     * @param  Collection<int, PageSection>  $sections
     * @return array<int, array<string, string|null>>
     */
    protected function resolveMembershipOffers(Collection $sections): array
    {
        $offerSections = $sections->filter(
            fn (PageSection $section): bool => $section->section_key === 'membership-offers'
                || ($section->section_key === null && $section->section_type === 'cards')
        );

        return $offerSections
            ->flatMap(function (PageSection $section): array {
                return collect($section->data['items'] ?? [])
                    ->map(fn (array $item): array => [
                        'title' => $item['title'] ?? null,
                        'description' => $item['content'] ?? null,
                        'url' => $item['link_url'] ?? null,
                        'linkLabel' => $item['link_label'] ?? null,
                    ])
                    ->filter(fn (array $item): bool => filled($item['title']) || filled($item['description']))
                    ->values()
                    ->all();
            })
            ->values()
            ->all();
    }

    /**
     * @param  Collection<int, PageSection>  $sections
     * @return array<int, string>
     */
    protected function resolvePracticalNotes(Collection $sections): array
    {
        return $sections
            ->filter(fn (PageSection $section): bool => str_starts_with((string) $section->section_key, 'practical-note'))
            ->flatMap(function (PageSection $section): array {
                $notes = [];

                if (filled($section->content)) {
                    $notes[] = $section->content;
                }

                foreach ($section->data['notes'] ?? [] as $note) {
                    if (is_array($note) && filled($note['content'] ?? null)) {
                        $notes[] = $note['content'];
                    }
                }

                return $notes;
            })
            ->values()
            ->all();
    }

    /**
     * @param  Collection<int, PageSection>  $sections
     * @return array<int, array<string, string|null>>
     */
    protected function resolveFaqItems(Collection $sections): array
    {
        return $sections
            ->filter(fn (PageSection $section): bool => $section->section_key === 'membership-faq' || $section->section_type === 'faq')
            ->flatMap(function (PageSection $section): array {
                return collect($section->data['items'] ?? [])
                    ->map(fn (array $item): array => [
                        'question' => $item['title'] ?? null,
                        'answer' => $item['content'] ?? null,
                    ])
                    ->filter(fn (array $item): bool => filled($item['question']) || filled($item['answer']))
                    ->values()
                    ->all();
            })
            ->values()
            ->all();
    }

    /**
     * @param  Collection<string, ExternalLink>  $links
     */
    protected function resolveLinkUrl(Collection $links, string $preferredKey, string $fallbackKey): ?string
    {
        return $links->get($preferredKey)?->url ?? $links->get($fallbackKey)?->url;
    }
}
