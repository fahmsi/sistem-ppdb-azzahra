<?php

return [
    'bank_name' => env('PPDB_BANK_NAME', '-'),
    'bank_account_number' => env('PPDB_BANK_ACCOUNT_NUMBER', '-'),
    'bank_account_holder' => env('PPDB_BANK_ACCOUNT_HOLDER', '-'),
    'admin_whatsapp' => env('PPDB_ADMIN_WHATSAPP', ''),
    'daftar_ulang_amount' => (int) env('PPDB_DAFTAR_ULANG_AMOUNT', 0),
];
