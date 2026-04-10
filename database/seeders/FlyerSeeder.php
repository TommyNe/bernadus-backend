<?php

namespace Database\Seeders;

use App\Models\Flyer;
use Illuminate\Database\Seeder;

class FlyerSeeder extends Seeder
{
    public function run(): void
    {
        Flyer::factory()->create([
            'title' => 'Vereinsflyer 2026',
            'pdf_path' => 'flyers/vereinsflyer-2026.pdf',
            'original_filename' => 'vereinsflyer-2026.pdf',
            'file_size' => 523_000,
            'is_active' => true,
            'uploaded_at' => now(),
        ]);
    }
}
