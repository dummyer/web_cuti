<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CutiController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\UserController;

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

Route::group(['middleware' => ['web']], function () {
	Route::get('/', [HomeController::class, 'index']);
	Route::get('/login', [LoginController::class, 'login']);
	Route::get('/logout', [LoginController::class, 'logout']);
	Route::get('/login/authLogin', [LoginController::class, 'authLogin']);
	Route::get('/home', [HomeController::class, 'index']);
	Route::get('/profile', [UserController::class, 'index']);
	Route::post('/profile/editPassword', [UserController::class, 'editPassword']);
	
	Route::get('/kalender_cuti', [CutiController::class, 'kalender_cuti']);
	Route::get('/permintaan_cuti', [CutiController::class, 'permintaan_cuti']);
	Route::get('/request_cuti', [CutiController::class, 'request_cuti']);
	Route::get('/info_cuti', [CutiController::class, 'info_cuti']);
	Route::get('/create_cuti_hr', [CutiController::class, 'create_cuti_hr']);
	Route::post('/create_cuti_hr/createCuti', [CutiController::class, 'createCuti']);
	Route::get('/permintaan_cuti/updateCuti', [CutiController::class, 'updateCuti']);
	Route::post('/request_cuti/requestCuti', [CutiController::class, 'requestCuti']);
	
	//show
	Route::get('/showAll_cutiRequest_pending', [CutiController::class, 'showAll_cutiRequest_pending']);
	Route::get('/showAll_cutiRequest_approved', [CutiController::class, 'showAll_cutiRequest_approved']);
	Route::get('/countCutiRequest_pending', [CutiController::class, 'countCutiRequest_pending']);
	Route::get('/showCalender', [CutiController::class, 'showCalender']);
	Route::get('/getKategoriCuti', [CutiController::class, 'getKategoriCuti']);
	Route::get('/getReqCuti_OneUser', [CutiController::class, 'getReqCuti_OneUser']);
	Route::get('/getOneUser_cuti6BulanTerakhir', [CutiController::class, 'getOneUser_cuti6BulanTerakhir']);
	Route::get('/getDetailCuti6BulanTerakhir', [CutiController::class, 'getDetailCuti6BulanTerakhir']);
});