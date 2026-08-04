<?php

use App\Models\User;
use Spatie\Permission\Models\Role;

beforeEach(function () {
    Role::firstOrCreate(['name' => 'user']);
    Role::firstOrCreate(['name' => 'admin']);
});

test('authenticated user can view profile', function () {
    $user = User::factory()->create()->assignRole('user');

    $this->actingAs($user)
        ->get(route('profile'))
        ->assertOk();
});

test('guest is redirected from profile to login', function () {
    $this->get(route('profile'))
        ->assertRedirect(route('login'));
});

test('user can update their name and email', function () {
    $user = User::factory()->create()->assignRole('user');

    $this->actingAs($user)
        ->putJson(route('profile.update'), [
            'name' => 'Updated Name',
            'email' => $user->email,
        ])->assertJson(['message' => 'Profile updated.']);

    $this->assertDatabaseHas('users', [
        'id' => $user->id,
        'name' => 'Updated Name',
    ]);
});

test('profile update requires valid email', function () {
    $user = User::factory()->create()->assignRole('user');

    $this->actingAs($user)
        ->putJson(route('profile.update'), [
            'name' => 'Name',
            'email' => 'not-an-email',
        ])->assertJsonValidationErrors(['email']);
});

test('user can change their password', function () {
    $user = User::factory()->create()->assignRole('user');

    $this->actingAs($user)
        ->putJson(route('profile.password'), [
            'current_password' => 'password',
            'password' => 'newpassword1',
            'password_confirmation' => 'newpassword1',
        ])->assertJson(['message' => 'Password changed.']);
});
