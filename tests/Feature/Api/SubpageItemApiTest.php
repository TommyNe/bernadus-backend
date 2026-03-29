<?php

use App\Models\Medium;
use App\Models\Person;
use App\Models\Role;
use App\Models\RoleAssignment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('returns people, roles and assignments with linked records', function () {
    $medium = Medium::factory()->create([
        'path' => 'portraits/maria.jpg',
    ]);

    $person = Person::factory()->for($medium, 'portrait')->create([
        'display_name' => 'Maria Beckmann',
    ]);

    $role = Role::factory()->create([
        'role_key' => 'vorsitzende',
        'name' => 'Vorsitzende',
    ]);

    $assignment = RoleAssignment::factory()->for($person)->for($role)->create([
        'label_override' => '1. Vorsitzende',
    ]);

    $this->getJson('/api/people')
        ->assertSuccessful()
        ->assertJsonPath('data.0.display_name', 'Maria Beckmann')
        ->assertJsonPath('data.0.portrait.path', 'portraits/maria.jpg');

    $this->getJson('/api/roles/vorsitzende')
        ->assertSuccessful()
        ->assertJsonPath('data.role_key', 'vorsitzende')
        ->assertJsonPath('data.assignments.0.person.display_name', 'Maria Beckmann');

    $this->getJson('/api/role-assignments/'.$assignment->id)
        ->assertSuccessful()
        ->assertJsonPath('data.label_override', '1. Vorsitzende')
        ->assertJsonPath('data.role.role_key', 'vorsitzende');
});

it('returns media and users', function () {
    $medium = Medium::factory()->create([
        'filename' => 'fest.jpg',
        'path' => 'uploads/fest.jpg',
    ]);

    $user = User::factory()->admin()->create([
        'name' => 'Admin User',
        'email' => 'admin@example.com',
    ]);

    $this->getJson('/api/media/'.$medium->id)
        ->assertSuccessful()
        ->assertJsonPath('data.filename', 'fest.jpg')
        ->assertJsonPath('data.path', 'uploads/fest.jpg');

    $this->getJson('/api/users')
        ->assertSuccessful()
        ->assertJsonCount(1, 'data')
        ->assertJsonPath('data.0.name', 'Admin User')
        ->assertJsonMissingPath('data.0.password');

    $this->getJson('/api/users/'.$user->id)
        ->assertSuccessful()
        ->assertJsonPath('data.email', 'admin@example.com')
        ->assertJsonPath('data.is_admin', true);
});
