<?php

return [
    'bank_name' => env('SPMB_BANK_NAME', '-'),
    'bank_account_number' => env('SPMB_BANK_ACCOUNT_NUMBER', '-'),
    'bank_account_holder' => env('SPMB_BANK_ACCOUNT_HOLDER', '-'),
    'admin_whatsapp' => env('SPMB_ADMIN_WHATSAPP', ''),
    'daftar_ulang_amount' => (int) env('SPMB_DAFTAR_ULANG_AMOUNT', 0),
];
