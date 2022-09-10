<?php
    Route::group([
        'prefix' => 'sys-check-table',
        'middleware' => 'web',
        'namespace' =>  'CustomTable\Controllers'
    ],function(){
        Route::post('check-editing', "CheckController@checkEditing");
        Route::post('remove-editing', "CheckController@removeEditing");
    });

?>