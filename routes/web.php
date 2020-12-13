<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Auth::routes();

Route::group(['middleware' => ['auth']], function() {
    Route::get('/', function () {
	    return view('dashboard');
	});

	Route::get('/home', function () {
	    return redirect('dashboard');
	});

	Route::get('/dashboard', 'DashboardController@index')->name('home');

	Route::view('/upload', 'upload');

    Route::post('/file/upload', 'FileController@upload');

    // Route::post('/store', 'FileController@store');

	require __DIR__ . '/profile/profile.php';
	require __DIR__ . '/users/users.php';
	require __DIR__ . '/customers/customers.php';
	require __DIR__ . '/services/services.php';
	require __DIR__ . '/roles/roles.php';
	require __DIR__ . '/roles/permissions.php';
	require __DIR__ . '/modules/modules.php';
});


