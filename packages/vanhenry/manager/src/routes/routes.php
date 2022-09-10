<?php
$admincp = \Config::get('manager.admincp');
Route::group(['prefix' => $admincp, 'middleware' => 'web', 'namespace' => 'vanhenry\manager\controller'], function () {
    Route::get('/', 'Admin@index');
    Route::get('table-lang/{table}/{locale}', array('uses' => "Admin@tableLang"));
    Route::get('view/{table}', array('uses' => "Admin@view"));
    Route::get('trashview/{table}', array('uses' => "Admin@trashview"));
    Route::any('getData/{table}', array('uses' => "Admin@getData"));
    Route::any('getDataTableSelect', array('uses' => "Admin@getDataTableSelect"));
    Route::any('getDataNotId/{table}', array('uses' => "Admin@getDataNotId"));
    Route::any('getDataPivot/{table}', array('uses' => "Admin@getDataPivot"));
    Route::post('get-district-from-province', array('uses' => "Admin@getDistrictByProvince"));
    Route::post('getDataWardByDistrict', array('uses' => "Admin@getWardByDistrict"));
    Route::any('getRecursive/{table}', ["uses" => "Admin@getRecursive"]);
    Route::match(['get', 'post'], 'search/{table}', array('uses' => "Admin@search"));
    Route::get('404', ['as' => '404', 'uses' => "Admin@view404"]);
    Route::get('no_permission', ['as' => 'no_permission', 'uses' => "Admin@noPermission"]);
    Route::post('delete/{table}', array('uses' => "Admin@delete"));
    Route::post('trash/{table}', array('uses' => "Admin@trash"));
    Route::post('backtrash/{table}', array('uses' => "Admin@backtrash"));
    Route::post('deleteAll/{table}', array('uses' => "Admin@deleteAll"));
    Route::post('trashAll/{table}', array('uses' => "Admin@trashAll"));
    Route::post('backTrashAll/{table}', array('uses' => "Admin@backTrashAll"));
    Route::post('activeAll/{table}', array('uses' => "Admin@activeAll"));
    Route::post('unActiveAll/{table}', array('uses' => "Admin@unActiveAll"));
    Route::get('viewdetail/{table}/{id}', array('uses' => "Admin@viewDetail"));
    Route::get('edit/{table}/{id}', array('uses' => "Admin@edit"));
    Route::post('edit/{table}/{id}', array('uses' => "Admin@edit"));
    Route::get('insert/{table}', array('uses' => "Admin@insert"));
    Route::get('copy/{table}/{id}', array('uses' => "Admin@copy"));
    Route::post('update/{table}/{id}', array('uses' => "Admin@update"));
    Route::post('save/{table}/{id}', array('uses' => "Admin@save"));
    Route::post('store/{table}', array('uses' => "Admin@store"));
    Route::post('storeAjax/{table}', array('uses' => "Admin@storeAjax"));
    Route::post('addAllToParent/{table}', array('uses' => "Admin@addAllToParent"));
    Route::post('removeFromParent/{table}', array('uses' => "Admin@removeFromParent"));
    Route::post('editableajax/{table}', array('uses' => "Admin@editableAjax"));
    //Menu
    Route::match(['get', 'post'], 'getDataMenu/{table}', array('uses' => "Admin@getDataMenu"));
    Route::match(['get', 'post'], 'getStaticMenu', array('uses' => "Admin@getStaticMenu"));
    Route::match(['get', 'post'], 'export/{table}', array('uses' => "Admin@exportExcel"));

    // Export file pdf from data is html
    Route::get('test-pdf', array('uses' => 'Admin@testPdf'));
    //Import
    Route::get('import/{table}', array('uses' => "Admin@import"));
    Route::post('do_import/{table}', array('uses' => "Admin@do_import"));
    //Phaan quyen
    Route::post('do_assign/{table}', array('uses' => "Admin@do_assign"));
    Route::post('getCrypt', array('uses' => "Admin@getCrypt"));
    //Khác
    Route::post('changepass', array('uses' => "Admin@changePass"));
    //Ngôn ngữ
    Route::get('changelang/{lang}', array('uses' => "Admin@changeLanguage"));
    //JBIG
    Route::get('view_user/{userid}', array('uses' => "Admin@view_user"))->where(['userid' => '[0-9]+']);
    //
    Route::get('login', 'AuthController@getLogin');
    Route::post('login', 'AuthController@postLogin');
    Route::get('register', 'AuthController@getRegister');
    Route::post('register', 'AuthController@postRegister');
    Route::get('logout', 'AuthController@logout');
    //Media
    Route::get('media/manager', 'MediaController@showmedia');
    Route::get('media/view', 'MediaController@media');
    Route::get('media/trash', 'MediaController@trash');
    Route::post('media/createDir', 'MediaController@createDir');
    Route::post('media/getInfoLasted', 'MediaController@getInfoLasted');
    Route::post('media/deleteFolder/{type?}', 'MediaController@deleteFolder')->where(['type' => '[0-9]+']); //->where(['id' => '[0-9]+', 'name' => '[a-z]+']);
    Route::post('media/uploadFile', 'MediaController@uploadFile');
    Route::post('media/uploadFileWm', 'MediaController@uploadFileWm');
    Route::post('media/upload-file-zip', array('uses' => 'MediaController@uploadFileZip'));
    Route::post('media/restore', 'MediaController@restoreFile');
    Route::post('media/getInfoFileLasted', 'MediaController@getInfoFileLasted');
    Route::post('media/deleteFile/{type?}', 'MediaController@deleteFile')->where(['type' => '[0-9]+']);
    Route::post('media/deleteAll/{type?}', 'MediaController@deleteAll')->where(['type' => '[0-9]+']);
    Route::post('media/copyFile', 'MediaController@copyFile');
    Route::post('media/moveFile', 'MediaController@moveFile');
    Route::post('media/listFolder', 'MediaController@listFolder');
    Route::post('media/listFolderMove', 'MediaController@listFolderMove');
    Route::post('media/getDetailFile', 'MediaController@getDetailFile');
    Route::post('media/saveDetailFile', 'MediaController@saveDetailFile');
    Route::post('media/duplicateFile', 'MediaController@duplicateFile');
    Route::post('media/rename', 'MediaController@rename');
    //Sys
    Route::get('vtableview', 'SysController@inserttable');
    Route::get('onoffmenu', 'SysController@onOffMenu');
    Route::get('onoffmenu', 'SysController@onOffMenu');
    Route::post('inserttableview', 'SysController@doinserttable');
    Route::get('deleteCache', 'SysController@deleteCache');
    Route::match(['get', 'post'], 'editRobot', 'SysController@editRobot');
    Route::get('editSitemap', 'SysController@editSitemap');
    Route::post('updateSitemap', 'SysController@updateSitemap');
    Route::post('change-type-menu', array('uses' => "SysController@changeTypeMenu"));
});
