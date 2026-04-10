<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;

uses(RefreshDatabase::class);

it('renders the contact page without embedding editorial content in the inertia payload', function () {
    $this->get('/kontakt')
        ->assertSuccessful()
        ->assertInertia(fn (Assert $page) => $page
            ->component('contact')
            ->where('pageTitle', 'Kontakt')
            ->where('contentEndpoint', '/api/pages/kontakt/content')
            ->missing('contactContent'));
});
