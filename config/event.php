<?php

return [
    'lucky' => [
        '<3000000' => [
            'SK' => 0.25,
            'TT' => 0.25,
            'MM' => 0.35,
            'BM' => 0.15
        ],
        '<30000000' => [
            'SK' => 0.3,
            'TT' => 0.3,
            'MM' => 0,
            'BM' => 0.4
        ],
        '>=30000000' => [
            'SK' => 0,
            'TT' => 0,
            'MM' => 0,
            'BM' => 1
        ]
    ],
    'lucky_name' => [
        'SK' => 'Vé sức khỏe',
        'TT' => 'Hộp tri thức',
        'MM' => 'Chúc may mắn',
        'BM' => 'Hộp bí mật'
    ]
];
