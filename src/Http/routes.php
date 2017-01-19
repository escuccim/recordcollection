<?php
Route::group(['middleware' => ['web']], function() {
    /* Records Resource */
    Route::get('records/search', 'RecordsController@search');
    Route::post('records/search', 'RecordsController@search');
    Route::resource('records', 'RecordsController');
});
Route::group(['middleware' => ['auth:api']], function() {
    Route::get('/api/records', 'RecordsController@api')->middleware('auth:api');
});

Route::group(['middleware' => ['auth:api']], function(){
   Route::get('/api/records', 'RecordsController@api');
});