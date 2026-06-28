<?php

use App\Models\User;

test('registration screen can be rendered', function () {
    $response = $this->get('/register');

    $response->assertStatus(200);
});

test('registration requires legal consent', function () {
    $response = $this->post('/register', [
        'name' => 'Test User',
        'email' => 'test@gmail.com',
        'password' => 'Password123',
        'password_confirmation' => 'Password123',
    ]);

    $response->assertSessionHasErrors('terms_accepted');
    $this->assertGuest();
    $this->assertDatabaseMissing('users', ['email' => 'test@gmail.com']);
});

test('new users can register with legal consent', function () {
    $response = $this->post('/register', [
        'name' => 'Test User',
        'email' => 'test@gmail.com',
        'password' => 'Password123',
        'password_confirmation' => 'Password123',
        'terms_accepted' => '1',
    ]);

    $this->assertAuthenticated();
    $response->assertRedirect(route('parent.dashboard', absolute: false));

    $user = User::where('email', 'test@gmail.com')->firstOrFail();

    expect($user->terms_accepted_at)->not->toBeNull()
        ->and($user->privacy_accepted_at)->not->toBeNull()
        ->and($user->terms_version)->toBe(User::TERMS_VERSION)
        ->and($user->privacy_version)->toBe(User::PRIVACY_VERSION)
        ->and($user->terms_accepted_ip)->toBe('127.0.0.1')
        ->and($user->privacy_accepted_ip)->toBe('127.0.0.1');
});

test('legal pages can be rendered publicly', function (string $routeName, string $heading) {
    $this->get(route($routeName))
        ->assertOk()
        ->assertSee($heading);
})->with([
    ['terms', 'Syarat dan Ketentuan Penggunaan'],
    ['privacy', 'Kebijakan Privasi dan Penggunaan Data'],
]);
