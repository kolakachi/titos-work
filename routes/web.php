<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\loginController;


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
    return view('welcome');
});



Route::post('/adminlogin', [loginController::class,'Adminlogin']);
Route::post('/fileuploads', [loginController::class,'FileUploads']);
Route::get('/uploadpage', [loginController::class,'uploadpage']);
Route::get('/dashboard', [loginController::class,'dashboardpg']);
Route::get('/viewxmll/{id}', [loginController::class,'viewxml']);
Route::get('/Exportxmls/{id}', [loginController::class,'Exportxml']);
Route::post('/createxmls', [xmlController::class,'createxml']);
  