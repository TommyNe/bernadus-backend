<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            ContentFoundationSeeder::class,
            TrophyShootingSeeder::class,
            PlaqueShootingSeeder::class,
            AdminUserSeeder::class,
        ]);
    }
}
