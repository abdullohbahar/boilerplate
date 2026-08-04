<?php

use App\Models\User;
use Spatie\Permission\Models\Role;

beforeEach(function () {
    // Disable CAPTCHA and throttle for all auth tests
    config(['captcha.enabled' => false, 'auth.login_throttle' => false]);
    Role::firstOrCreate(['name' => 'user']);
    Role::firstOrCreate(['name' => 'admin']);
});

test('login page is accessible', function () {
    $this->get(route('login'))->assertOk();
});

test('user can login with correct credentials', function () {
    $user = User::factory()->create();

    $this->post(route('login'), [
        'email' => $user->email,
        'password' => 'password',
    ])->assertRedirect('/');

    $this->assertAuthenticatedAs($user);
});

test('login fails with wrong password', function () {
    $user = User::factory()->create();

    $this->post(route('login'), [
        'email' => $user->email,
        'password' => 'wrong-password',
    ])->assertSessionHasErrors(['email']);

    $this->assertGuest();
});

test('register page is accessible', function () {
    $this->get(route('register'))->assertOk();
});

test('user can register', function () {
    $this->post(route('register'), [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ])->assertRedirect('/');

    $this->assertDatabaseHas('users', ['email' => 'test@example.com']);
});

test('authenticated user can logout', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->post(route('logout'))
        ->assertRedirect(route('login'));

    $this->assertGuest();
});

test('authenticated user is redirected away from login', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route('login'))
        ->assertRedirect('/');
});
