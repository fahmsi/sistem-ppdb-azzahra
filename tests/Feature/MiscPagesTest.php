<?php

test('misc error page can be rendered', function () {
    $response = $this->get('/misc/error');

    $response->assertStatus(200);
    $response->assertSee(route('home'), false);
    $response->assertSee(route('login'), false);
    $response->assertSee('Terjadi Kesalahan Sistem');
});

test('misc maintenance page can be rendered', function () {
    $response = $this->get('/misc/maintenance');

    $response->assertStatus(200);
    $response->assertSee(route('home'), false);
    $response->assertSee(route('login'), false);
    $response->assertSee('Sistem Sedang Dalam Pemeliharaan');
});

test('misc coming soon page can be rendered', function () {
    $response = $this->get('/misc/coming-soon');

    $response->assertStatus(200);
    $response->assertSee(route('home'), false);
    $response->assertSee(route('login'), false);
    $response->assertSee('Sesuatu yang Menarik Segera Hadir!');
});

test('misc not authorized page can be rendered', function () {
    $response = $this->get('/misc/not-authorized');

    $response->assertStatus(200);
    $response->assertSee(route('home'), false);
    $response->assertSee(route('login'), false);
    $response->assertSee('Akses Ditolak');
});

test('misc page expired can be rendered', function () {
    $response = $this->get('/misc/page-expired');

    $response->assertStatus(200);
    $response->assertSee(route('home'), false);
    $response->assertSee(route('login'), false);
    $response->assertSee('Sesi Halaman Telah Kedaluwarsa');
});
