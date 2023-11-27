<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

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

// Route::get('/', function () {
//     return view('welcome');
// });

require __DIR__ . '/auth.php';

Route::get('/getBlobPhoto/{id?}/{fieldName?}', 'NonAdminAnalyticsController@getBlobPhoto')->name("getBlobPhoto");
Route::get('cameras/camview/{id}', 'ApplianceController@getCameraView');
Route::get('unit_history/{unitId}', 'SsoController@unitHistory');
Route::get('permissions', 'SsoController@permissions');
Route::get('more_building_settings', 'SsoController@moreBuildingSettings');
Route::get('api/doorAction/{id}/{action}', 'SsoController@unlockAssetDoor');
Route::get('/manageLockdown', 'SsoController@manageLockdown');
Route::get('/manage_carts', 'SsoController@manageCarts');
Route::get('/sso/{url?}', 'SsoController@ssoForAdmin');
Route::get('/building/manage/{id?}', 'SsoController@manageBuilding');
Route::get('/downloadWorkOrderPDF/{id?}', 'WorkOrderController@downloadWorkOrderPDF');

Route::get('/uploads/{filename}', function ($filename) {
    if (Storage::exists('uploads/' . $filename)) {
        return Storage::response('uploads/' . $filename);
    } else {
        abort(404);
    }
})->where('filename', '.*');

Route::get('/portfolio_owners', function () {
    return view('layouts.vue');
})->where('any', '.*')->middleware(['auth', 'check_admin']);

Route::get('/login_as/{userId}', 'AdminActionsController@loginAs')->middleware(['auth', 'check_admin']);

Route::get('/{any}', function () {
    return view('layouts.vue');
})->where('any', '.*')->middleware(['auth', 'check_owner']);
