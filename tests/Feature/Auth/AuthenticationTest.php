<?php

use App\Models\User;

test('login screen can be rendered', function () {
    $response = $this->get('/login');

    $response->assertStatus(200);
});

test('users can authenticate using the login screen', function () {
    $user = User::factory()->create(['role' => 'parent']);

    $response = $this->post('/login', [
        'email' => $user->email,
        'password' => 'password',
    ]);

    $this->assertAuthenticated();
    $response->assertRedirect(route('parent.dashboard', absolute: false));
});

test('admins are redirected to the admin dashboard after login', function () {
    $user = User::factory()->create(['role' => 'admin']);

    $response = $this->post('/login', [
        'email' => $user->email,
        'password' => 'password',
    ]);

    $this->assertAuthenticated();
    $response->assertRedirect(route('admin.dashboard', absolute: false));
});

test('authenticated users visiting login are redirected to their dashboard', function (string $role, string $dashboard) {
    $user = User::factory()->create(['role' => $role]);

    $response = $this->actingAs($user)->get('/login');

    $response->assertRedirect(route($dashboard, absolute: false));
})->with([
    'parent' => ['parent', 'parent.dashboard'],
    'admin' => ['admin', 'admin.dashboard'],
    'super admin' => ['super_admin', 'admin.dashboard'],
]);

test('users can not authenticate with invalid password', function () {
    $user = User::factory()->create(['role' => 'parent']);

    $this->post('/login', [
        'email' => $user->email,
        'password' => 'wrong-password',
    ]);

    $this->assertGuest();
});

test('users can logout', function () {
    $user = User::factory()->create(['role' => 'parent']);

    $response = $this->actingAs($user)->post('/logout');

    $this->assertGuest();
    $response->assertRedirect(route('login', absolute: false));
});
