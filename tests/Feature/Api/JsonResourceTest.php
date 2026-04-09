<?php

use App\Http\Resources\ChronicleEntryResource;
use App\Http\Resources\ChronicleResource;
use App\Http\Resources\CompetitionResource;
use App\Http\Resources\CompetitionResultCategoryResource;
use App\Http\Resources\CompetitionResultResource;
use App\Http\Resources\CompetitionTypeResource;
use App\Http\Resources\EventResource;
use App\Http\Resources\ExternalLinkResource;
use App\Http\Resources\MediumResource;
use App\Http\Resources\PageResource;
use App\Http\Resources\PageSectionResource;
use App\Http\Resources\PersonResource;
use App\Http\Resources\PlaqueAwardRuleResource;
use App\Http\Resources\RoleAssignmentResource;
use App\Http\Resources\RoleResource;
use App\Http\Resources\UserResource;
use App\Http\Resources\VenueResource;
use App\Models\Chronicle;
use App\Models\ChronicleEntry;
use App\Models\Competition;
use App\Models\CompetitionResult;
use App\Models\CompetitionResultCategory;
use App\Models\CompetitionType;
use App\Models\Event;
use App\Models\ExternalLink;
use App\Models\Medium;
use App\Models\Page;
use App\Models\PageSection;
use App\Models\Person;
use App\Models\PlaqueAwardRule;
use App\Models\Role;
use App\Models\RoleAssignment;
use App\Models\User;
use App\Models\Venue;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;

uses(RefreshDatabase::class);

function resourcePayload(string $resourceClass, mixed $resource): array
{
    return (new $resourceClass($resource))
        ->toResponse(Request::create('/'))
        ->getData(true)['data'];
}

it('serializes pages and page sections', function () {
    $page = Page::factory()->create([
        'slug' => 'satzung',
        'title' => 'Satzung',
        'status' => 'published',
    ]);

    $section = PageSection::factory()->for($page)->create([
        'section_key' => 'hero-intro',
        'section_type' => 'hero',
        'title' => 'Willkommen',
    ]);

    $pagePayload = resourcePayload(PageResource::class, $page->load('sections'));
    resourcePayload(PageSectionResource::class, $section->load('page'));

    expect($pagePayload['slug'])->toBe('satzung')
        ->and($pagePayload['title'])->toBe('Satzung')
        ->and($pagePayload['published_at'])->toBeString()
        ->and($pagePayload['sections'][0]['section_key'])->toBe('hero-intro')
        ->and($pagePayload['sections'][0]['section_type'])->toBe('hero');
});

it('serializes people, media, roles and users', function () {
    $medium = Medium::factory()->create([
        'disk' => 'public',
        'path' => 'portraits/anna.jpg',
        'filename' => 'anna.jpg',
    ]);

    $person = Person::factory()->for($medium, 'portrait')->create([
        'display_name' => 'Anna Becker',
    ]);

    $role = Role::factory()->create([
        'role_key' => 'vorsitz',
        'name' => 'Vorsitz',
    ]);

    $assignment = RoleAssignment::factory()->for($person)->for($role)->create([
        'label_override' => '1. Vorsitzende',
    ]);

    $user = User::factory()->admin()->withTwoFactor()->create([
        'name' => 'Admin',
        'email' => 'admin@example.com',
    ]);

    $mediumPayload = resourcePayload(MediumResource::class, $medium);
    $personPayload = resourcePayload(PersonResource::class, $person->load('portrait', 'roleAssignments.role'));
    $rolePayload = resourcePayload(RoleResource::class, $role->load('assignments'));
    $assignmentPayload = resourcePayload(RoleAssignmentResource::class, $assignment->load('person', 'role'));
    $userPayload = resourcePayload(UserResource::class, $user);

    expect($mediumPayload['path'])->toBe('portraits/anna.jpg')
        ->and($mediumPayload['filename'])->toBe('anna.jpg')
        ->and($mediumPayload['url'])->toContain('portraits/anna.jpg')
        ->and($personPayload['display_name'])->toBe('Anna Becker')
        ->and($personPayload['portrait']['path'])->toBe('portraits/anna.jpg')
        ->and($personPayload['role_assignments'][0]['label_override'])->toBe('1. Vorsitzende')
        ->and($rolePayload['role_key'])->toBe('vorsitz')
        ->and($rolePayload['assignments'][0]['label_override'])->toBe('1. Vorsitzende')
        ->and($assignmentPayload['label_override'])->toBe('1. Vorsitzende')
        ->and($assignmentPayload['person']['display_name'])->toBe('Anna Becker')
        ->and($assignmentPayload['role']['role_key'])->toBe('vorsitz')
        ->and($userPayload['name'])->toBe('Admin')
        ->and($userPayload['email'])->toBe('admin@example.com')
        ->and($userPayload['is_admin'])->toBeTrue()
        ->and($userPayload)->not->toHaveKeys(['password', 'two_factor_secret', 'remember_token']);
});

it('serializes events, venues, competitions and results', function () {
    $venue = Venue::factory()->create([
        'name' => 'Schuetzenhalle',
        'city' => 'Olfen',
    ]);

    $event = Event::factory()->for($venue)->create([
        'slug' => 'fruehjahrsschiessen',
        'title' => 'Fruehjahrsschiessen',
    ]);

    $type = CompetitionType::factory()->create([
        'type_key' => 'pokal',
        'name' => 'Pokal',
    ]);

    $competition = Competition::factory()->for($type, 'type')->for($event)->create([
        'title' => 'Pokalrunde',
        'year' => 2026,
    ]);

    $category = CompetitionResultCategory::factory()->for($competition)->create([
        'name' => 'Senioren',
    ]);

    $person = Person::factory()->create([
        'display_name' => 'Max Mustermann',
    ]);

    $result = CompetitionResult::factory()->for($category, 'category')->for($person)->create([
        'winner_name' => 'Max Mustermann',
        'rank' => 1,
    ]);

    $rule = PlaqueAwardRule::factory()->for($competition)->create([
        'award_name' => 'Goldene Plakette',
        'rule_type' => 'gold_milestone',
    ]);

    $venuePayload = resourcePayload(VenueResource::class, $venue->load('events'));
    $eventPayload = resourcePayload(EventResource::class, $event->load('venue', 'competitions'));
    $typePayload = resourcePayload(CompetitionTypeResource::class, $type->load('competitions'));
    $competitionPayload = resourcePayload(CompetitionResource::class, $competition->load('type', 'event', 'resultCategories', 'plaqueAwardRules'));
    $categoryPayload = resourcePayload(CompetitionResultCategoryResource::class, $category->load('competition', 'results'));
    $resultPayload = resourcePayload(CompetitionResultResource::class, $result->load('category', 'person'));
    $rulePayload = resourcePayload(PlaqueAwardRuleResource::class, $rule->load('competition'));

    expect($venuePayload['name'])->toBe('Schuetzenhalle')
        ->and($venuePayload['events'][0]['slug'])->toBe('fruehjahrsschiessen')
        ->and($eventPayload['slug'])->toBe('fruehjahrsschiessen')
        ->and($eventPayload['venue']['name'])->toBe('Schuetzenhalle')
        ->and($eventPayload['competitions'][0]['title'])->toBe('Pokalrunde')
        ->and($typePayload['type_key'])->toBe('pokal')
        ->and($typePayload['competitions'][0]['title'])->toBe('Pokalrunde')
        ->and($competitionPayload['title'])->toBe('Pokalrunde')
        ->and($competitionPayload['type']['type_key'])->toBe('pokal')
        ->and($competitionPayload['status'])->toBe('published')
        ->and($competitionPayload['published_at'])->toBeString()
        ->and($competitionPayload['event']['slug'])->toBe('fruehjahrsschiessen')
        ->and($competitionPayload['result_categories'][0]['name'])->toBe('Senioren')
        ->and($competitionPayload['plaque_award_rules'][0]['award_name'])->toBe('Goldene Plakette')
        ->and($categoryPayload['name'])->toBe('Senioren')
        ->and($categoryPayload['competition']['title'])->toBe('Pokalrunde')
        ->and($categoryPayload['results'][0]['winner_name'])->toBe('Max Mustermann')
        ->and($resultPayload['winner_name'])->toBe('Max Mustermann')
        ->and($resultPayload['category']['name'])->toBe('Senioren')
        ->and($resultPayload['person']['display_name'])->toBe('Max Mustermann')
        ->and($rulePayload['award_name'])->toBe('Goldene Plakette')
        ->and($rulePayload['competition']['title'])->toBe('Pokalrunde');
});

it('serializes chronicles and chronicle entries', function () {
    $medium = Medium::factory()->create([
        'path' => 'chronicles/1908.jpg',
    ]);

    $chronicle = Chronicle::factory()->create([
        'chronicle_key' => 'verein',
        'title' => 'Vereinschronik',
    ]);

    $entry = ChronicleEntry::factory()->for($chronicle)->for($medium, 'image')->create([
        'year' => 1908,
        'headline' => 'Gruendungsjahr',
    ]);

    $chroniclePayload = resourcePayload(ChronicleResource::class, $chronicle->load('entries'));
    $entryPayload = resourcePayload(ChronicleEntryResource::class, $entry->load('chronicle', 'image'));

    expect($chroniclePayload['chronicle_key'])->toBe('verein')
        ->and($chroniclePayload['title'])->toBe('Vereinschronik')
        ->and($chroniclePayload['entries'][0]['year'])->toBe(1908)
        ->and($entryPayload['year'])->toBe(1908)
        ->and($entryPayload['headline'])->toBe('Gruendungsjahr')
        ->and($entryPayload['chronicle']['chronicle_key'])->toBe('verein')
        ->and($entryPayload['image']['path'])->toBe('chronicles/1908.jpg');
});

it('serializes external links', function () {
    $link = ExternalLink::factory()->create([
        'link_key' => 'verband',
        'label' => 'Verband',
        'url' => 'https://example.com/verband',
    ]);

    $payload = resourcePayload(ExternalLinkResource::class, $link);

    expect($payload['link_key'])->toBe('verband')
        ->and($payload['label'])->toBe('Verband')
        ->and($payload['url'])->toBe('https://example.com/verband');
});
