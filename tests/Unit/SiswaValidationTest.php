<?php

use App\Http\Requests\StoreSiswaRequest;
use App\Http\Requests\UpdateSiswaRequest;
use App\Models\Siswa;

it('requires nama panggilan when creating and updating siswa', function () {
    $expectedRules = ['required', 'string', 'max:50'];

    expect((new StoreSiswaRequest())->rules()['nama_panggilan'])->toBe($expectedRules)
        ->and((new UpdateSiswaRequest())->rules()['nama_panggilan'])->toBe($expectedRules);
});

it('allows nama panggilan to be mass assigned', function () {
    expect((new Siswa())->isFillable('nama_panggilan'))->toBeTrue();
});
