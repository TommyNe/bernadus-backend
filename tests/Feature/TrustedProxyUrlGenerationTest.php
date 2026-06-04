<?php

use Illuminate\Support\Facades\Route;

it('generates https urls when traefik forwards the original scheme', function () {
    Route::get('/trusted-proxy-url-generation', function (): array {
        return [
            'is_secure' => request()->isSecure(),
            'livewire_update_url' => url('/livewire-cae72194/update'),
        ];
    });

    $this->withHeaders([
        'X-Forwarded-For' => '203.0.113.10',
        'X-Forwarded-Host' => 'bernadus-backend.tinnen.digital',
        'X-Forwarded-Port' => '443',
        'X-Forwarded-Proto' => 'https',
    ])
        ->get('/trusted-proxy-url-generation')
        ->assertSuccessful()
        ->assertJsonPath('is_secure', true)
        ->assertJsonPath('livewire_update_url', 'https://bernadus-backend.tinnen.digital/livewire-cae72194/update');
});
