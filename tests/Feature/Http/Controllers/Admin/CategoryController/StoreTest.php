<?php

use App\Models\User;
use Illuminate\Support\Str;

beforeEach(function () {
    $this->actingAs(User::factory()->create());
});

test('can create a category', function () {
    $data = [
        'name' => 'family',
        'color' => '#41a2a3',
    ];

    $this->post(route('categories.store'), $data)
        ->assertRedirectBackWithoutErrors();

    $this->assertDatabaseCount('categories', 1);
    $this->assertDatabaseHas('categories', $data);
});

test('can create a category without color', function () {
    $this->post(route('categories.store'), [
        'name' => 'friends',
    ])->assertRedirectBackWithoutErrors();

    $this->assertDatabaseCount('categories', 1);
    $this->assertDatabaseHas('categories', [
        'name' => 'friends',
        'color' => '#d1d1d1',
    ]);
});

test('cannot create a category with invalid data', function ($properties, $key, $error) {
    $data = array_replace([
        'name' => 'family',
        'color' => '#41a2a3',
    ], $properties);

    $this->post(route('categories.store'), $data)
        ->assertRedirectBackWithErrors([$key => $error]);

    $this->assertDatabaseCount('categories', 0);
})->with([
    'empty name' => [
        ['name' => ''],
        'name',
        'The name field is required.',
    ],
    'name is not a string' => [
        ['name' => 1],
        'name',
        'The name field must be a string.',
    ],
    'name is longer than the maximum length' => [
        ['name' => Str::repeat('a', 21)],
        'name',
        'The name field must not be greater than 20 characters.',
    ],
    'color is not a string' => [
        ['color' => 1],
        'color',
        'The color field must be a string.',
    ],
    'color is not hex color' => [
        ['color' => '#zzzzzz'],
        'color',
        'The color field must be a valid hexadecimal color.',
    ],
]);

test('cannot store categories when unauthenticated', function () {
    $this->actingAsGuest();
    $this->post(route('categories.store'), ['name' => 'family'])
        ->assertRedirect(route('login'));

    $this->assertDatabaseCount('categories', 0);
});
