<?php

// config for Homeful/Products
return [
    'default' => [
        'processing_fee' => env('PROCESSING_FEE', 10000),
        'percent_dp' => env('PERCENT_DP', 10/100),
        'dp_term' => env('DP_TERM', 12), //months
        'percent_mf' => env('PERCENT_MF', 8.5/100),
    ],
];
