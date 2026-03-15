<?php

namespace Database\Seeders;

use App\Models\NavigationItem;
use Illuminate\Database\Seeder;

class NavigationItemSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            [
                'title' => 'Start',
                'slug' => 'start',
                'path' => '/',
                'children' => [],
            ],
            [
                'title' => 'Über uns',
                'slug' => 'ueber-uns',
                'path' => '/ueber-uns',
                'children' => [
                    ['title' => 'Verein & Geschichte', 'slug' => 'verein-geschichte', 'path' => '/ueber-uns/verein-geschichte'],
                    ['title' => 'Funktionäre', 'slug' => 'funktionaere', 'path' => '/ueber-uns/funktionaere'],
                    ['title' => 'Thron heute', 'slug' => 'thron-heute', 'path' => '/ueber-uns/thron-heute'],
                    ['title' => 'Thron früher', 'slug' => 'thron-frueher', 'path' => '/ueber-uns/thron-frueher'],
                    ['title' => 'Vereinshymne', 'slug' => 'vereinshymne', 'path' => '/ueber-uns/vereinshymne'],
                ],
            ],
            [
                'title' => 'Termine & Events',
                'slug' => 'termine-events',
                'path' => '/termine-events',
                'children' => [
                    ['title' => 'Termine', 'slug' => 'termine', 'path' => '/termine-events/termine'],
                    ['title' => 'Pokalschießen', 'slug' => 'pokalschiessen', 'path' => '/termine-events/pokalschiessen'],
                    ['title' => 'Plakettenschießen', 'slug' => 'plakettenschiessen', 'path' => '/termine-events/plakettenschiessen'],
                ],
            ],
            [
                'title' => 'Service & Materialien',
                'slug' => 'service-materialien',
                'path' => '/service-materialien',
                'children' => [
                    ['title' => 'Flyer', 'slug' => 'flyer', 'path' => '/service-materialien/flyer'],
                    ['title' => 'Beitrittserklärung', 'slug' => 'beitrittserklaerung', 'path' => '/service-materialien/beitrittserklaerung'],
                    ['title' => 'Links', 'slug' => 'links', 'path' => '/service-materialien/links'],
                ],
            ],
            [
                'title' => 'Galerie & Ehrungen',
                'slug' => 'galerie-ehrungen',
                'path' => '/galerie-ehrungen',
                'children' => [
                    ['title' => 'Bilder & Ehrungen', 'slug' => 'bilder-ehrungen', 'path' => '/galerie-ehrungen/bilder-ehrungen'],
                ],
            ],
            [
                'title' => 'Mitmachen',
                'slug' => 'mitmachen',
                'path' => '/mitmachen',
                'children' => [
                    ['title' => 'Kinderkönig', 'slug' => 'kinderkoenig', 'path' => '/mitmachen/kinderkoenig'],
                    ['title' => 'Jackenbörse', 'slug' => 'jackenboerse', 'path' => '/mitmachen/jackenboerse'],
                    ['title' => 'WhatsApp Newsletter', 'slug' => 'whatsapp-newsletter', 'path' => '/mitmachen/whatsapp-newsletter'],
                ],
            ],
            [
                'title' => 'Kontakt',
                'slug' => 'kontakt',
                'path' => '/kontakt',
                'children' => [
                    ['title' => 'Kontaktformular & Info', 'slug' => 'kontaktformular-info', 'path' => '/kontakt/kontaktformular-info'],
                ],
            ],
        ];

        foreach ($items as $index => $item) {
            $this->createNavigationItem($item, null, $index);
        }
    }

    /**
     * @param  array{title: string, slug: string, path: string, children?: array<int, array{title: string, slug: string, path: string}>}  $item
     */
    private function createNavigationItem(array $item, ?NavigationItem $parent, int $sortOrder): NavigationItem
    {
        $navigationItem = NavigationItem::query()->create([
            'parent_id' => $parent?->id,
            'title' => $item['title'],
            'slug' => $item['slug'],
            'path' => $item['path'],
            'sort_order' => $sortOrder,
            'is_active' => true,
        ]);

        foreach ($item['children'] ?? [] as $childIndex => $child) {
            $this->createNavigationItem($child, $navigationItem, $childIndex);
        }

        return $navigationItem;
    }
}
