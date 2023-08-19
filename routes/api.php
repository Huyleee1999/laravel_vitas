<?php

use App\Http\Controllers\Api\v1\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\v1\BannersController;
use App\Http\Controllers\Api\v1\BookingsController;
use App\Http\Controllers\Api\v1\CityController;
use App\Http\Controllers\Api\v1\EvaluatesController;
use App\Http\Controllers\Api\v1\UsersRegisterController;
use App\Http\Controllers\Api\v1\ExpertRegisterController;
use App\Http\Controllers\Api\v1\UsersLoginController;
use App\Http\Controllers\Api\v1\ExpertController;
use App\Http\Controllers\Api\v1\ProfessionController;
use App\Http\Controllers\Api\v1\FeatureProgramsController;
use App\Http\Controllers\Api\v1\QuestionsController;
use App\Http\Controllers\Api\v1\TagProgramsController;
use App\Http\Controllers\Api\v1\TagsController;
use App\Http\Controllers\Api\v1\ExpertDetailController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::prefix('v1')->middleware('api')->group(function () {
    Route::resource('banners', BannersController::class)->only('show', 'update', 'destroy', 'store', 'index');

    // Route::get('banners', [BannersController::class, 'index']);

    Route::resource('user-register', UsersRegisterController::class)->only('index', 'store', 'show');

    Route::resource('expert-register', ExpertRegisterController::class)->only('index', 'store', 'show');

    Route::post('user-login', [UsersLoginController::class, 'login']);

    Route::resource('expert', ExpertController::class)->only('index', 'store', 'show', 'update', 'destroy');

    Route::resource('professions', ProfessionController::class)->only('index', 'store', 'show', 'update', 'destroy');

    Route::resource('featureprograms', FeatureProgramsController::class)->only('index', 'store', 'show', 'update', 'destroy');

    Route::resource('city', CityController::class)->only('show', 'index');

    // Route::get('city', [CityController::class, 'index']);

    Route::resource('tags', TagsController::class)->only('index', 'store', 'show', 'update', 'destroy');

    Route::resource('questions', QuestionsController::class)->only('index', 'store', 'show', 'update', 'destroy');

    Route::resource('evaluates', EvaluatesController::class)->only('index', 'store', 'show', 'update', 'destroy');

    Route::resource('tagprograms', TagProgramsController::class)->only('index', 'store', 'update', 'destroy');

    Route::resource('expert-detail', ExpertDetailController::class)->only('index', 'show', 'update', 'destroy');

    Route::resource('bookings', BookingsController::class)->only('index', 'show', 'update', 'destroy', 'store');

});

Route::group(['middleware' => 'api', 'prefix' => 'auth/v1'], function($router) {
    Route::post('register', [ AuthController::class, 'register']);
    Route::post('login', [ AuthController::class, 'login']);
    Route::get('profile', [ AuthController::class, 'profile']);
    Route::post('logout', [ AuthController::class, 'logout']);
});