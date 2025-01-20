<?php

return [
    'datetime_format' => 'Y-m-d\TH:i:sP',
    'tokens_expire_in_minutes' => env('PASSPORT_TOKENS_EXPIRE_IN_MINUTES', 30),
    'refresh_tokens_expire_in_days' => env('PASSPORT_REFRESH_TOKENS_EXPIRE_IN_DAYS', 30),
];
