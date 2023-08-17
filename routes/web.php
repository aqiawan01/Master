<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\Role\IndexController as RoleController;
use App\Http\Controllers\Admin\Permission\IndexController as PermissionController;
use App\Http\Controllers\Admin\User\IndexController as UserController;

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

Route::get('/', function () {
    return redirect('/login');
});

Auth::routes([
    'register' => false, // Registration Routes...
    'reset' => false, // Password Reset Routes...
    'verify' => false, // Email Verification Routes...
]);

Route::get('/home', function () {
    return redirect('/admin/users');
});

Route::group(['namespace' => 'Admin', 'prefix' => 'admin', 'middleware' => ['auth']], function(){

    Route::get('/', function () {
		return redirect('/admin/users');
	});

	Route::group(['namespace' => 'Role'], function (){
		Route::get('roles', [RoleController::class,'index'])->name('roles.index')->middleware('permission:role-list');
		Route::get('roles/create', [RoleController::class,'create'])->name('roles.create')->middleware('permission:role-create');
		Route::post('roles', [RoleController::class,'store'])->name('roles.store')->middleware('permission:role-create');
		Route::get('roles/{id}/edit', [RoleController::class,'edit'])->name('roles.edit')->middleware('permission:role-edit');
		Route::put('roles/{id}', [RoleController::class,'update'])->name('roles.update')->middleware('permission:role-edit');
		Route::any('roles/{id}/destroy', [RoleController::class,'destroy'])->name('roles.destroy')->middleware('permission:role-delete');
	});

	Route::group(['namespace' => 'Permission'], function (){
		Route::get('permissions', [PermissionController::class,'index'])->name('permissions.index')->middleware('permission:permission-list');
		Route::get('permissions/create', [PermissionController::class,'create'])->name('permissions.create')->middleware('permission:permission-create');
		Route::post('permissions', [PermissionController::class,'store'])->name('permissions.store')->middleware('permission:permission-create');
		Route::get('permissions/{id}/edit', [PermissionController::class,'edit'])->name('permissions.edit')->middleware('permission:permission-edit');
		Route::put('permissions/{id}', [PermissionController::class,'update'])->name('permissions.update')->middleware('permission:permission-edit');
		Route::any('permissions/{id}/destroy', [PermissionController::class,'destroy'])->name('permissions.destroy')->middleware('permission:permission-delete');
	});

	Route::group(['namespace' => 'User'], function (){
		Route::get('users', [UserController::class,'index'])->name('users.index')->middleware('permission:user-list');
		Route::get('users/create', [UserController::class,'create'])->name('users.create')->middleware('permission:user-create');
		Route::post('users', [UserController::class,'store'])->name('users.store')->middleware('permission:user-create');
		Route::get('users/{id}/edit', [UserController::class,'edit'])->name('users.edit')->middleware('permission:user-edit');
		Route::put('users/{id}', [UserController::class,'update'])->name('users.update')->middleware('permission:user-edit');
		Route::any('users/{id}/destroy', [UserController::class,'destroy'])->name('users.destroy')->middleware('permission:user-delete');
	});

});
