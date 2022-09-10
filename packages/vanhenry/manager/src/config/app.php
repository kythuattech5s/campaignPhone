<?php

return [
    'providers' => [
        vanhenry\helpers\HelperServiceProvider::class,
        vanhenry\customblade\CustomBladeServiceProvider::class,
        CustomTable\Providers\CustomTableProvider::class
    ],
    'aliases' => [
        'FCHelper' => vanhenry\helpers\helpers\FCHelper::class,
        'StringHelper' => vanhenry\helpers\helpers\StringHelper::class,
        'SettingHelper' => vanhenry\helpers\helpers\SettingHelper::class,
        'SEOHelper' => vanhenry\helpers\helpers\SEOHelper::class
    ]
];
