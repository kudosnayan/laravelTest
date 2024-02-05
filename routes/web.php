<?php

use App\Http\Controllers\OpenGoogleSheetController;
use App\Http\Controllers\PhonePecontroller;
use App\Http\Controllers\UserController;
use App\Mail\SendTestEmail;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;
use Predis\Client;

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
    $client = new Client();

    // Set a value
    $client->set('name', 'Nayan Raval');
    
    // Get a value
    $name = $client->get('name');

    dd(
        $name
    );

    // Mail::to('nayan@yopmail.com')->send(new SendTestEmail());
    return view('welcome');
});
Route::get('/user/{id}', [UserController::class, 'getUserDetails']);
Route::get('phonepe',[PhonePecontroller::class,'phonePe']);
Route::post('phonepe-response',[PhonePeController::class,'response'])->name('response');



Route::get('/userss/form',  [UserController::class, 'createForm']);
Route::post('/user/store',  [UserController::class, 'store']);

Route::get('/open-google-sheet/{user}', [OpenGoogleSheetController::class,'openGoogleSheet']);
