<?php

Route::group([
    'namespace' => 'Tech5s\LoginSocial\Controllers',
    'middleware' => 'web'
], function () {
    Route::get('login-social/{social}', 'LoginController@redirectToSocial')->name('login-social');
    Route::match(['get','post'],'callback-{social}', 'LoginController@handleProviderCallback')->name('callback-social');
});
