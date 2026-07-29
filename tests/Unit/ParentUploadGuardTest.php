<?php

test('every active parent file input uses the centralized two megabyte guard', function () {
    $resourceDirectory = dirname(__DIR__, 2).DIRECTORY_SEPARATOR.'resources';
    $viewFiles = [
        $resourceDirectory.'/views/parent/siswa/create.blade.php',
        $resourceDirectory.'/views/parent/siswa/edit.blade.php',
        $resourceDirectory.'/views/parent/pendaftaran/status.blade.php',
        $resourceDirectory.'/views/profile/edit.blade.php',
    ];

    foreach ($viewFiles as $viewFile) {
        $contents = file_get_contents($viewFile);
        preg_match_all('/<input\b[^>]*type="file"[^>]*>/s', $contents, $matches);

        expect($matches[0])->not->toBeEmpty();

        foreach ($matches[0] as $fileInput) {
            expect($fileInput)
                ->toContain('data-file-input')
                ->toContain('data-file-max-size="2097152"')
                ->toContain('data-file-error=');
        }
    }
});

test('parent upload helper contains accessible validation alert and session draft safeguards', function () {
    $resourceDirectory = dirname(__DIR__, 2).DIRECTORY_SEPARATOR.'resources';
    $helper = file_get_contents($resourceDirectory.'/js/parent-upload-guard.js');
    $createView = file_get_contents($resourceDirectory.'/views/parent/siswa/create.blade.php');
    $editView = file_get_contents($resourceDirectory.'/views/parent/siswa/edit.blade.php');

    expect($helper)
        ->toContain('2 * 1024 * 1024')
        ->toContain('Ukuran file melebihi batas maksimal 2 MB.')
        ->toContain('Ukuran File Terlalu Besar')
        ->toContain("setAttribute('aria-invalid', 'true')")
        ->toContain('sessionStorage')
        ->not->toContain('localStorage')
        ->and($createView)
        ->toContain('data-parent-draft-form')
        ->toContain('spmb:parent:siswa:create:')
        ->and($editView)
        ->toContain('data-parent-draft-form')
        ->toContain('spmb:parent:siswa:edit:');
});
