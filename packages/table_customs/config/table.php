<?php

return [
    'edit' => ['checkExit','checkEdit'],
    'copy' => [''],
    'add' => [''],
    // 
    'checkExit' => [
        'news' => []
    ],
    'checkEdit' => [
        'news' => [
            'time' => 5000,
            'class' => '\CustomTable\Controllers\CheckController',
            'method' => 'checkTimeEdit'
        ]
    ]
];