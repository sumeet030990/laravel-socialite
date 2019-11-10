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

use App\Http\Controllers\Auth\LoginController;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return view('welcome');
})->middleware('guest');

Route::get('login/{service}', 'Auth\LoginController@redirectToProvider');
Route::get('login/{service}/callback', 'Auth\LoginController@handleProviderCallback');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::post('/service-scope-user', 'ServiceScopeUserController@store')->name('serviceScopeUser.store');
Route::get('/getServicesPendingPermissions', 'ServiceScopeUserController@getServicesPendingPermissions')->name('serviceScopeUser.getServicesPendingPermissions');

Route::get('/test', function(){
    dd(Auth::user()->permissionGivenScopes->where('loginType_id', 2)->load('scope'));
});
