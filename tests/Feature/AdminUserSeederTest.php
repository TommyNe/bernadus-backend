<?php

use App\Models\User;
use Database\Seeders\AdminUserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

uses(RefreshDatabase::class);

it('creates an admin user via the seeder', function () {
    $this->seed(AdminUserSeeder::class);
    $this->seed(AdminUserSeeder::class);

    $adminUser = User::query()->where('email', 'admin@bernadus.test')->first();

    expect($adminUser)->not->toBeNull();
    expect(User::query()->where('email', 'admin@bernadus.test')->count())->toBe(1);
    expect($adminUser->name)->toBe('Admin');
    expect($adminUser->is_admin)->toBeTrue();
    expect($adminUser->email_verified_at)->not->toBeNull();
    expect(Hash::check('password', $adminUser->password))->toBeTrue();
});
