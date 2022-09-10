<?php
$saveCache = base_path('storage\framework\cache\data\login-social');
$fileCache = base_path('storage\framework\cache\data\login-social\json');

if (!file_exists($fileCache)) {
    $services = [
        'facebook' => [
            'client_id' => DB::table('configs')->where('name', 'FB_CLIENT_ID')->first(['vi_value'])->vi_value,
            'client_secret' => DB::table('configs')->where('name', 'FB_CLIENT_SECRET')->first(['vi_value'])->vi_value,
            'redirect' => url('callback-facebook')
        ],
        'google' => [
            'client_id' => DB::table('configs')->where('name', 'GG_CLIENT_ID')->first(['vi_value'])->vi_value,
            'client_secret' => DB::table('configs')->where('name', 'GG_CLIENT_SECRET')->first(['vi_value'])->vi_value,
            'redirect' => url('callback-google')
        ]
    ];
    if (!is_dir($saveCache) && !file_exists($saveCache)) {
        mkdir($saveCache, 0777, true);
    };
    file_put_contents($fileCache, json_encode($services, JSON_PRETTY_PRINT));
} else {
    $services = json_decode(file_get_contents($fileCache), true);
}

return $services;
