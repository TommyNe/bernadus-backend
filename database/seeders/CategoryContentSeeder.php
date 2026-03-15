<?php

namespace Database\Seeders;

use App\Models\AboutSection;
use App\Models\ContactEntry;
use App\Models\EventItem;
use App\Models\GalleryHonor;
use App\Models\ParticipationOption;
use App\Models\ServiceMaterial;
use App\Models\StartPage;
use Illuminate\Database\Seeder;

class CategoryContentSeeder extends Seeder
{
    public function run(): void
    {
        StartPage::query()->create([
            'title' => 'Start',
            'slug' => 'start',
            'path' => '/',
            'summary' => 'Einstiegspunkt der Website.',
            'content' => 'Die Startseite dient als zentraler Einstieg in alle Bereiche des Vereins.',
            'sort_order' => 0,
            'is_active' => true,
        ]);

        $this->seedItems(AboutSection::class, '/ueber-uns', [
            'Verein & Geschichte',
            'Funktionäre',
            'Thron heute',
            'Thron früher',
            'Vereinshymne',
        ]);

        $this->seedItems(EventItem::class, '/termine-events', [
            'Termine',
            'Pokalschießen',
            'Plakettenschießen',
        ]);

        $this->seedItems(ServiceMaterial::class, '/service-materialien', [
            'Flyer',
            'Beitrittserklärung',
            'Links',
        ]);

        $this->seedItems(GalleryHonor::class, '/galerie-ehrungen', [
            'Bilder & Ehrungen',
        ]);

        $this->seedItems(ParticipationOption::class, '/mitmachen', [
            'Kinderkönig',
            'Jackenbörse',
            'WhatsApp Newsletter',
        ]);

        $this->seedItems(ContactEntry::class, '/kontakt', [
            'Kontaktformular & Info',
        ]);
    }

    /**
     * @param  class-string<\Illuminate\Database\Eloquent\Model>  $modelClass
     * @param  list<string>  $titles
     */
    private function seedItems(string $modelClass, string $basePath, array $titles): void
    {
        foreach ($titles as $index => $title) {
            $slug = str($title)->lower()->ascii()->replace(' ', '-')->replace('&', 'und')->replace('--', '-')->value();
            $slug = trim($slug, '-');

            $modelClass::query()->create([
                'title' => $title,
                'slug' => $slug,
                'path' => $basePath.'/'.$slug,
                'summary' => $title.' als eigener Inhaltsbereich.',
                'content' => 'Inhalt fuer '.$title.' innerhalb der Kategorie '.$basePath.'.',
                'sort_order' => $index,
                'is_active' => true,
            ]);
        }
    }
}
