<?php

use App\Models\User;
use Spatie\Permission\Models\Role;

beforeEach(function () {
    Role::firstOrCreate(['name' => 'admin']);
    Role::firstOrCreate(['name' => 'user']);
});

test('admin can access user list', function () {
    $admin = User::factory()->create()->assignRole('admin');

    $this->actingAs($admin)
        ->get(route('admin.users.index'))
        ->assertOk();
});

test('regular user cannot access user list', function () {
    $user = User::factory()->create()->assignRole('user');

    $this->actingAs($user)
        ->get(route('admin.users.index'))
        ->assertForbidden();
});

test('guest is redirected from admin to login', function () {
    $this->get(route('admin.users.index'))
        ->assertRedirect(route('login'));
});

test('admin can access roles list', function () {
    $admin = User::factory()->create()->assignRole('admin');

    $this->actingAs($admin)
        ->get(route('admin.roles.index'))
        ->assertOk();
});

test('admin can view activity log', function () {
    $admin = User::factory()->create()->assignRole('admin');

    $this->actingAs($admin)
        ->get(route('admin.activity.index'))
        ->assertOk();
});

test('admin can create a user', function () {
    $admin = User::factory()->create()->assignRole('admin');

    $this->actingAs($admin)
        ->postJson(route('admin.users.store'), [
            'name' => 'New User',
            'email' => 'newuser@example.com',
            'role' => 'user',
            'password' => 'password',
            'password_confirmation' => 'password',
        ])->assertJson(['redirect' => route('admin.users.index')]);

    $this->assertDatabaseHas('users', ['email' => 'newuser@example.com']);
});

test('admin can delete a user', function () {
    $admin = User::factory()->create()->assignRole('admin');
    $user = User::factory()->create()->assignRole('user');

    $this->actingAs($admin)
        ->delete(route('admin.users.destroy', $user))
        ->assertRedirect();

    $this->assertDatabaseMissing('users', ['id' => $user->id]);
});
