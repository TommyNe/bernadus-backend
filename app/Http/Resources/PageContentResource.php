<?php

namespace App\Http\Resources;

use App\Models\ExternalLink;
use App\Models\Flyer;
use App\Models\MembershipDocument;
use App\Models\Page;
use App\Models\PageSection;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;

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
        /** @var MembershipDocument|null $applicationDocument */
        $applicationDocument = $this->resource['applicationDocument'];
        /** @var Flyer|null $flyerDocument */
        $flyerDocument = $this->resource['flyerDocument'];
        $sections = $page->sections;
        $heroSection = $this->resolveHeroSection($page, $sections);
        $introSection = $this->resolveIntroSection($page, $sections);
        $contactCards = $this->resolveContactCards($page, $sections, $links);
        $officialLinks = $this->resolveOfficialLinks($page, $sections, $links);
        $address = $this->resolveContactAddress($page, $sections);
        $notes = $this->resolveContactNotes($page, $sections);

        return [
            'slug' => $page->slug,
            'title' => $page->title,
            'hero' => [
                'eyebrow' => is_array($heroSection?->data) ? ($heroSection->data['eyebrow'] ?? null) : null,
                'title' => $heroSection?->title ?? $page->title,
                'subtitle' => $heroSection?->subtitle,
                'description' => $heroSection?->content,
                'intro' => $heroSection?->content,
            ],
            'intro' => $introSection?->content,
            'introTitle' => $introSection?->title,
            'flyerUrl' => $this->resolveFlyerUrl($flyerDocument, $links, sprintf('page.%s.flyer', $page->slug), 'official_flyer'),
            'contactUrl' => $this->resolveLinkUrl($links, sprintf('page.%s.contact', $page->slug), 'official_contact'),
            'applicationUrl' => $applicationDocument ? Storage::disk('public')->url($applicationDocument->pdf_path) : null,
            'membershipOffers' => $this->resolveMembershipOffers($page, $sections),
            'practicalNotes' => $this->resolvePracticalNotes($page, $sections),
            'faq' => $this->resolveFaqItems($page, $sections),
            'contactCards' => $contactCards,
            'officialLinks' => $officialLinks,
            'address' => $address,
            'notes' => $notes,
            'contact' => $page->slug === 'kontakt' ? [
                'cards' => $contactCards,
                'officialLinks' => $officialLinks,
                'address' => $address,
                'notes' => $notes,
            ] : null,
            'applicationDocument' => $this->transformMembershipDocument($applicationDocument),
            'flyerDocument' => $this->transformFlyerDocument($flyerDocument),
            'documents' => array_values(array_filter([
                $this->transformMembershipDocument($applicationDocument),
                $this->transformFlyerDocument($flyerDocument),
            ])),
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
    protected function resolveHeroSection(Page $page, Collection $sections): ?PageSection
    {
        foreach ($this->preferredSectionKeys($page, 'hero') as $sectionKey) {
            $section = $this->findSectionByKey($sections, $sectionKey);

            if ($section !== null) {
                return $section;
            }
        }

        return $sections->first(
            fn (PageSection $section): bool => $section->section_type === 'hero'
        );
    }

    /**
     * @param  Collection<int, PageSection>  $sections
     */
    protected function resolveIntroSection(Page $page, Collection $sections): ?PageSection
    {
        foreach ($this->preferredSectionKeys($page, 'intro') as $sectionKey) {
            $section = $this->findSectionByKey($sections, $sectionKey);

            if ($section !== null && filled($section->content)) {
                return $section;
            }
        }

        $heroSection = $this->resolveHeroSection($page, $sections);

        if ($page->slug === 'mitglied-werden' && $heroSection !== null && filled($heroSection->content)) {
            return $heroSection;
        }

        return $sections->first(
            fn (PageSection $section): bool => $section->section_type === 'rich_text' && filled($section->content)
        ) ?? $heroSection;
    }

    /**
     * @param  Collection<int, PageSection>  $sections
     * @return array<int, array<string, string|null|int>>
     */
    protected function resolveMembershipOffers(Page $page, Collection $sections): array
    {
        if ($page->slug !== 'mitglied-werden') {
            return [];
        }

        $offerSections = $sections->filter(
            fn (PageSection $section): bool => $section->section_key === 'membership-offers'
                || ($section->section_key === null && $section->section_type === 'cards')
        );

        return $offerSections
            ->flatMap(function (PageSection $section): array {
                $data = is_array($section->data) ? $section->data : [];

                return collect($data['items'] ?? [])
                    ->filter(fn (array $item): bool => (bool) ($item['is_active'] ?? true))
                    ->sortBy(fn (array $item): int => (int) ($item['sort_order'] ?? 0))
                    ->map(fn (array $item): array => [
                        'title' => $item['title'] ?? null,
                        'description' => $item['content'] ?? null,
                        'icon' => $item['icon'] ?? null,
                        'url' => $item['link_url'] ?? null,
                        'linkLabel' => $item['link_label'] ?? null,
                        'sortOrder' => (int) ($item['sort_order'] ?? 0),
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
    protected function resolvePracticalNotes(Page $page, Collection $sections): array
    {
        if ($page->slug !== 'mitglied-werden') {
            return [];
        }

        return $sections
            ->filter(fn (PageSection $section): bool => str_starts_with((string) $section->section_key, 'practical-note'))
            ->flatMap(function (PageSection $section): array {
                $data = is_array($section->data) ? $section->data : [];
                $notes = [];

                if (filled($section->content)) {
                    $notes[] = $section->content;
                }

                foreach ($data['notes'] ?? [] as $note) {
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
     * @return array<int, array<string, string|null|int>>
     */
    protected function resolveFaqItems(Page $page, Collection $sections): array
    {
        if ($page->slug !== 'mitglied-werden') {
            return [];
        }

        return $sections
            ->filter(fn (PageSection $section): bool => $section->section_key === 'membership-faq' || $section->section_type === 'faq')
            ->flatMap(function (PageSection $section): array {
                $data = is_array($section->data) ? $section->data : [];

                return collect($data['items'] ?? [])
                    ->filter(fn (array $item): bool => (bool) ($item['is_active'] ?? true))
                    ->sortBy(fn (array $item): int => (int) ($item['sort_order'] ?? 0))
                    ->map(fn (array $item): array => [
                        'question' => $item['title'] ?? null,
                        'answer' => $item['content'] ?? null,
                        'sortOrder' => (int) ($item['sort_order'] ?? 0),
                    ])
                    ->filter(fn (array $item): bool => filled($item['question']) || filled($item['answer']))
                    ->values()
                    ->all();
            })
            ->values()
            ->all();
    }

    /**
     * @param  Collection<int, PageSection>  $sections
     * @param  Collection<string, ExternalLink>  $links
     * @return array<int, array<string, string|null|int>>
     */
    protected function resolveContactCards(Page $page, Collection $sections, Collection $links): array
    {
        if ($page->slug !== 'kontakt') {
            return [];
        }

        $section = $this->findSectionByKey($sections, 'contact-options');

        if ($section === null) {
            return [];
        }

        $data = is_array($section->data) ? $section->data : [];

        return collect($data['items'] ?? [])
            ->filter(fn (array $item): bool => (bool) ($item['is_active'] ?? true))
            ->sortBy(fn (array $item): int => (int) ($item['sort_order'] ?? 0))
            ->map(fn (array $item): array => [
                'title' => $item['title'] ?? null,
                'description' => $item['content'] ?? null,
                'icon' => $item['icon'] ?? null,
                'url' => $this->resolveSectionItemUrl($item, $links),
                'linkLabel' => $this->resolveSectionItemLabel($item, $links),
                'linkKey' => $item['link_key'] ?? null,
                'sortOrder' => (int) ($item['sort_order'] ?? 0),
            ])
            ->filter(fn (array $item): bool => filled($item['title']) || filled($item['description']) || filled($item['url']))
            ->values()
            ->all();
    }

    /**
     * @param  Collection<int, PageSection>  $sections
     * @param  Collection<string, ExternalLink>  $links
     * @return array<int, array<string, string|null|int>>
     */
    protected function resolveOfficialLinks(Page $page, Collection $sections, Collection $links): array
    {
        if ($page->slug !== 'kontakt') {
            return [];
        }

        $section = $this->findSectionByKey($sections, 'contact-official-links');

        if ($section === null) {
            return [];
        }

        $data = is_array($section->data) ? $section->data : [];

        return collect($data['items'] ?? [])
            ->filter(fn (array $item): bool => (bool) ($item['is_active'] ?? true))
            ->sortBy(fn (array $item): int => (int) ($item['sort_order'] ?? 0))
            ->map(fn (array $item): array => [
                'title' => $item['title'] ?? null,
                'description' => $item['content'] ?? null,
                'url' => $this->resolveSectionItemUrl($item, $links),
                'linkLabel' => $this->resolveSectionItemLabel($item, $links),
                'linkKey' => $item['link_key'] ?? null,
                'sortOrder' => (int) ($item['sort_order'] ?? 0),
            ])
            ->filter(fn (array $item): bool => filled($item['title']) || filled($item['url']))
            ->values()
            ->all();
    }

    /**
     * @param  Collection<int, PageSection>  $sections
     * @return array<string, mixed>|null
     */
    protected function resolveContactAddress(Page $page, Collection $sections): ?array
    {
        if ($page->slug !== 'kontakt') {
            return null;
        }

        $section = $this->findSectionByKey($sections, 'contact-address');

        if ($section === null) {
            return null;
        }

        $data = is_array($section->data) ? $section->data : [];
        $postalCode = $data['postal_code'] ?? null;
        $city = $data['city'] ?? null;
        $postalLine = trim(implode(' ', array_filter([$postalCode, $city], fn (?string $value): bool => filled($value))));

        return [
            'title' => $section->title,
            'description' => $section->content,
            'name' => $data['name'] ?? null,
            'street' => $data['street'] ?? null,
            'postalCode' => $postalCode,
            'city' => $city,
            'country' => $data['country'] ?? null,
            'notes' => $data['address_notes'] ?? $data['notes'] ?? null,
            'lines' => array_values(array_filter([
                $data['name'] ?? null,
                $data['street'] ?? null,
                filled($postalLine) ? $postalLine : null,
                $data['country'] ?? null,
            ])),
            'sortOrder' => (int) $section->sort_order,
        ];
    }

    /**
     * @param  Collection<int, PageSection>  $sections
     * @return array<int, array<string, string|null|int>>
     */
    protected function resolveContactNotes(Page $page, Collection $sections): array
    {
        if ($page->slug !== 'kontakt') {
            return [];
        }

        return $sections
            ->filter(function (PageSection $section): bool {
                return $section->section_key === 'contact-source-note'
                    || str_starts_with((string) $section->section_key, 'contact-note-');
            })
            ->sortBy('sort_order')
            ->map(fn (PageSection $section): array => [
                'title' => $section->title,
                'content' => $section->content,
                'tone' => is_array($section->data) ? ($section->data['tone'] ?? null) : null,
                'sortOrder' => (int) $section->sort_order,
            ])
            ->filter(fn (array $item): bool => filled($item['title']) || filled($item['content']))
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

    /**
     * @param  array<string, mixed>  $item
     * @param  Collection<string, ExternalLink>  $links
     */
    protected function resolveSectionItemUrl(array $item, Collection $links): ?string
    {
        $linkKey = $item['link_key'] ?? null;

        if (is_string($linkKey) && filled($linkKey) && $links->has($linkKey)) {
            return $links->get($linkKey)?->url;
        }

        return is_string($item['link_url'] ?? null) ? $item['link_url'] : null;
    }

    /**
     * @param  array<string, mixed>  $item
     * @param  Collection<string, ExternalLink>  $links
     */
    protected function resolveSectionItemLabel(array $item, Collection $links): ?string
    {
        if (is_string($item['link_label'] ?? null) && filled($item['link_label'])) {
            return $item['link_label'];
        }

        $linkKey = $item['link_key'] ?? null;

        if (is_string($linkKey) && filled($linkKey) && $links->has($linkKey)) {
            return $links->get($linkKey)?->label;
        }

        return null;
    }

    /**
     * @param  Collection<string, ExternalLink>  $links
     */
    protected function resolveFlyerUrl(?Flyer $flyerDocument, Collection $links, string $preferredKey, string $fallbackKey): ?string
    {
        if ($flyerDocument !== null) {
            return Storage::disk('public')->url($flyerDocument->pdf_path);
        }

        return $this->resolveLinkUrl($links, $preferredKey, $fallbackKey);
    }

    /**
     * @return array<int, string>
     */
    protected function preferredSectionKeys(Page $page, string $group): array
    {
        return match ($page->slug) {
            'mitglied-werden' => match ($group) {
                'hero' => ['membership-hero'],
                'intro' => ['membership-hero'],
                default => [],
            },
            'kontakt' => match ($group) {
                'hero' => ['contact-hero'],
                'intro' => ['contact-intro', 'contact-hero'],
                default => [],
            },
            default => [],
        };
    }

    /**
     * @param  Collection<int, PageSection>  $sections
     */
    protected function findSectionByKey(Collection $sections, string $sectionKey): ?PageSection
    {
        return $sections->first(
            fn (PageSection $section): bool => $section->section_key === $sectionKey
        );
    }

    /**
     * @return array<string, mixed>|null
     */
    protected function transformMembershipDocument(?MembershipDocument $document): ?array
    {
        if ($document === null) {
            return null;
        }

        return [
            'type' => $document->document_type,
            'title' => $document->title,
            'description' => $document->description,
            'url' => Storage::disk('public')->url($document->pdf_path),
            'filename' => $document->original_filename,
            'updatedAt' => $this->serializeDate($document->updated_at),
            'uploadedAt' => $this->serializeDate($document->uploaded_at),
        ];
    }

    /**
     * @return array<string, mixed>|null
     */
    protected function transformFlyerDocument(?Flyer $document): ?array
    {
        if ($document === null) {
            return null;
        }

        return [
            'type' => 'flyer',
            'title' => $document->title,
            'description' => null,
            'url' => Storage::disk('public')->url($document->pdf_path),
            'filename' => $document->original_filename,
            'updatedAt' => $this->serializeDate($document->updated_at),
            'uploadedAt' => $this->serializeDate($document->uploaded_at),
        ];
    }
}
