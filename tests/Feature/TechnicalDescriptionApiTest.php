<?php

use App\Models\Property;
use App\Models\TechnicalDescription;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Spatie\Permission\Models\Permission;

uses(RefreshDatabase::class);

function technicalDescriptionPayload(array $overrides = []): array
{
    return array_merge([
        'company_name' => 'Acme Properties',
        'registry_of_deeds' => 'Registry of Deeds Manila',
        'tct' => 'TCT-123',
        'vsr' => 'VSR-456',
        'old_technical_desc' => 'Legacy long-form technical description.',
        'survey_plan_no' => 'PSD-001',
        'block_no' => 'Block 1',
        'lot_no' => 'Lot 2',
        'portion_of_lot' => 'A portion of Lot 2',
        'lrc_record_no' => 'LRC-789',
        'land_owner_claimant' => 'Juan Dela Cruz',
        'location' => 'Manila',
        'area' => '120 sqm',
        'description_of_corners' => 'Corners described here.',
        'bearings' => true,
        'original_date_of_survey' => 'January 1, 2020',
        'date_of_survey' => 'February 1, 2020',
        'date_approved' => 'March 1, 2020',
        'geodetic_engineer' => 'Engineer Name',
    ], $overrides);
}

function createPropertyForTechnicalDescription(string $code): Property
{
    return Property::create([
        'code' => $code,
        'name' => 'Test Property',
        'type' => 'house',
    ]);
}

function actingAsApiUserWithTechnicalDescriptionPermissions(array $permissions = []): User
{
    $user = User::factory()->create();

    foreach ($permissions as $permission) {
        Permission::findOrCreate($permission, 'web');
        $user->givePermissionTo($permission);
    }

    Sanctum::actingAs($user);

    return $user;
}

test('technical description can be created by property code', function () {
    actingAsApiUserWithTechnicalDescriptionPermissions(['create_technical::description']);

    $property = createPropertyForTechnicalDescription('PROP-001');

    $response = $this->postJson(
        route('technical-description-create', ['property_code' => $property->code]),
        technicalDescriptionPayload(),
    );

    $response
        ->assertCreated()
        ->assertJsonPath('data.property_code', $property->code)
        ->assertJsonPath('data.old_technical_desc', 'Legacy long-form technical description.');

    $this->assertDatabaseHas('technical_descriptions', [
        'property_code' => $property->code,
        'old_technical_desc' => 'Legacy long-form technical description.',
    ]);
});

test('technical description can be updated by property code', function () {
    actingAsApiUserWithTechnicalDescriptionPermissions(['update_technical::description']);

    $property = createPropertyForTechnicalDescription('PROP-002');
    TechnicalDescription::create(array_merge(
        ['property_code' => $property->code],
        technicalDescriptionPayload(['old_technical_desc' => 'Old value.']),
    ));

    $response = $this->patchJson(
        route('technical-description-update', ['property_code' => $property->code]),
        ['old_technical_desc' => 'Updated old technical description.'],
    );

    $response
        ->assertOk()
        ->assertJsonPath('data.property_code', $property->code)
        ->assertJsonPath('data.old_technical_desc', 'Updated old technical description.');

    $this->assertDatabaseHas('technical_descriptions', [
        'property_code' => $property->code,
        'old_technical_desc' => 'Updated old technical description.',
    ]);
});

test('technical description can be viewed by an authorized user', function () {
    actingAsApiUserWithTechnicalDescriptionPermissions(['view_technical::description']);

    $property = createPropertyForTechnicalDescription('PROP-003');
    TechnicalDescription::create(array_merge(
        ['property_code' => $property->code],
        technicalDescriptionPayload(['old_technical_desc' => 'Visible technical description.']),
    ));

    $response = $this->getJson(
        route('technical-description-details', ['property_code' => $property->code]),
    );

    $response
        ->assertOk()
        ->assertJsonPath('data.property_code', $property->code)
        ->assertJsonPath('data.old_technical_desc', 'Visible technical description.');
});

test('technical description routes require authentication', function () {
    $property = createPropertyForTechnicalDescription('PROP-004');

    $this->getJson(route('technical-description-details', ['property_code' => $property->code]))
        ->assertUnauthorized();

    $this->postJson(
        route('technical-description-create', ['property_code' => $property->code]),
        technicalDescriptionPayload(),
    )->assertUnauthorized();
});

test('technical description routes reject users without required permissions', function () {
    Sanctum::actingAs(User::factory()->create());

    $property = createPropertyForTechnicalDescription('PROP-005');
    TechnicalDescription::create(array_merge(
        ['property_code' => $property->code],
        technicalDescriptionPayload(),
    ));

    $this->getJson(route('technical-description-details', ['property_code' => $property->code]))
        ->assertForbidden();

    $this->patchJson(
        route('technical-description-update', ['property_code' => $property->code]),
        ['old_technical_desc' => 'Unauthorized update.'],
    )->assertForbidden();
});

test('technical description create requires the create permission', function () {
    actingAsApiUserWithTechnicalDescriptionPermissions(['update_technical::description']);

    $property = createPropertyForTechnicalDescription('PROP-006');

    $this->postJson(
        route('technical-description-create', ['property_code' => $property->code]),
        technicalDescriptionPayload(),
    )->assertForbidden();
});

test('technical description update requires the update permission', function () {
    actingAsApiUserWithTechnicalDescriptionPermissions(['create_technical::description']);

    $property = createPropertyForTechnicalDescription('PROP-007');
    TechnicalDescription::create(array_merge(
        ['property_code' => $property->code],
        technicalDescriptionPayload(),
    ));

    $this->patchJson(
        route('technical-description-update', ['property_code' => $property->code]),
        ['old_technical_desc' => 'Unauthorized update.'],
    )->assertForbidden();
});

test('technical description rejects oversized text payloads', function () {
    actingAsApiUserWithTechnicalDescriptionPermissions(['create_technical::description']);

    $property = createPropertyForTechnicalDescription('PROP-008');

    $this->postJson(
        route('technical-description-create', ['property_code' => $property->code]),
        technicalDescriptionPayload(['old_technical_desc' => str_repeat('a', 10001)]),
    )
        ->assertUnprocessable()
        ->assertJsonValidationErrors('old_technical_desc');
});

test('technical description create validates property code existence', function () {
    actingAsApiUserWithTechnicalDescriptionPermissions(['create_technical::description']);

    $this->postJson(
        route('technical-description-create', ['property_code' => 'MISSING-PROP']),
        technicalDescriptionPayload(),
    )
        ->assertUnprocessable()
        ->assertJsonValidationErrors('property_code');
});
