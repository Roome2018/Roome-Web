<?php
Route::post('v1/user', 'Api\V1\UsersController@store');
Route::post('v1/resetpassword', 'Api\V1\UsersController@resetpassword');




Route::group(['prefix' => 'v1' , 'namespace' => 'Api\V1' ,'middleware' => ['auth:api']], function () {
        Route::get('user','UsersController@getifouth');
        Route::get('logout','UsersController@logout');

        Route::resource('rooms', 'RoomsController', ['except' => ['create', 'edit']]);

        Route::resource('comments', 'CommentsController', ['except' => ['create', 'edit']]);

        Route::resource('likes', 'LikesController', ['except' => ['create', 'edit']]);

        Route::resource('bookings', 'BookingsController', ['except' => ['create', 'edit']]);

        Route::post('/spatie/media/upload', 'SpatieMediaController@create');

});
