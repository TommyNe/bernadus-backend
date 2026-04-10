<?php

namespace Database\Seeders;

use App\Models\JacketListing;
use Illuminate\Database\Seeder;

class JacketListingSeeder extends Seeder
{
    public function run(): void
    {
        JacketListing::factory()
            ->published()
            ->count(3)
            ->sequence(
                [
                    'type' => 'Angebot',
                    'title' => 'Schützenjacke Größe 50',
                    'details' => 'Gut erhaltene Uniformjacke inklusive Schulterklappen, Abholung nach Absprache in Tinnen.',
                    'contact_hint' => 'Kontakt über das Vereinsbüro.',
                    'sort_order' => 10,
                ],
                [
                    'type' => 'Gesuch',
                    'title' => 'Damenjacke Größe 40/42',
                    'details' => 'Gesucht wird eine gepflegte Jacke für den Einstieg in den Verein. Kleine Anpassungen sind möglich.',
                    'contact_hint' => 'Bitte Nachricht an die Redaktion.',
                    'sort_order' => 20,
                ],
                [
                    'type' => 'Tausch',
                    'title' => 'Jacke Größe 54 gegen 52',
                    'details' => 'Vorhandene Jacke ist sehr ordentlich, fällt aber zu weit aus. Gesucht wird ein direkter Tausch.',
                    'contact_hint' => 'Übergabe nach Vereinbarung.',
                    'sort_order' => 30,
                ],
            )
            ->create();
    }
}
