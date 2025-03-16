<?php

use App\Http\Controllers\Api\V1\Dashboard\JobController;
use App\Http\Controllers\Api\V1\Dashboard\LocationController;
use App\Http\Controllers\Api\V1\Dashboard\ManagementController;
use App\Http\Controllers\Api\V1\Dashboard\ProjectStatusController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\HomeController;
use App\Http\Controllers\Api\V1\SystemController;
use App\Http\Controllers\Api\V1\Auth\LoginController;
use App\Http\Controllers\Api\V1\Auth\LogoutController;
use App\Http\Controllers\Api\V1\Dashboard\BlogController;
use App\Http\Controllers\Api\V1\Dashboard\CategoryController;
use App\Http\Controllers\Api\V1\Dashboard\MemberController;
use App\Http\Controllers\Api\V1\Dashboard\ProjectController;
use App\Http\Controllers\Api\V1\Dashboard\SettingController;
use App\Http\Controllers\helper\FileController;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');


/**
 * API v1 route file
 * @ V1
 * @ Authentication api route
 */



// Public route api/v1/
// Route::get('/', [HomeController::class, 'index']);
Route::get('/clear', [SystemController::class, 'clear']);


// Public route api/v1/projects
Route::get('/projects', [ProjectController::class, 'projects']);
Route::get('/project/{id}', [ProjectController::class, 'project']);
Route::get('/members', [MemberController::class, 'members']);
Route::get('/member/{id}', [MemberController::class, 'member']);
Route::get('/management-members', [ManagementController::class, 'managements']);
Route::get('/management-member/{id}', [ManagementController::class, 'management']);
Route::get('/locations', [LocationController::class, 'locations']);
Route::get('/location/{id}', [LocationController::class, 'location']);
Route::get('/project-statuses', [ProjectStatusController::class, 'ProjectStatuses']);
Route::get('/project-status/{id}', [ProjectStatusController::class, 'ProjectStatus']);
Route::get('/categories', [CategoryController::class, 'categories']);
Route::get('/blogs', [BlogController::class, 'blogs']);
Route::get('/blog/{id}', [BlogController::class, 'blog']);
Route::get('/homepage', [\App\Http\Controllers\Api\V1\Dashboard\HomeController::class, 'homePage']);
Route::post("/jobs/applicants/dropcv",[JobController::class,"dropCV"])->name("job.applicant.dropcv");
Route::get('/careers', [JobController::class, 'career']);


//  /api/v1/auth name='api.v1.'
// Route::group(['prefix' => 'auth', 'name' => 'auth'], function () {
//     Route::post('/login', LoginController::class); // api/v1/auth/login
//     Route::middleware('auth:sanctum')->group(function () {Route::post('/logout', LogoutController::class);}); // api/v1/auth/logout
// });

//  Dashboard | /api/v1/dashboard name='api.v1.dashboard'
// Route::group(['prefix' => 'dashboard', 'middleware' => 'auth:sanctum', 'name' => 'dashboard'], function () {

//     Route::get('/', [SettingController::class, 'index']);
//     Route::post('/settings/data', [SettingController::class, 'store_data']);
//     Route::post('/settings/user_info', [SettingController::class, 'user_info']); // update user info

//     // Files
//     Route::get('/file/{id}', [FileController::class, 'getFile']);
//     Route::post('/file/{id}', [FileController::class, 'updateFile']);
//     Route::patch('/file/{id}', [FileController::class, 'disableFile']); // diable file
//     Route::delete('/file/{id}', [FileController::class, 'deleteFile']);

//     // Projects
//     Route::post('/project/store', [ProjectController::class, 'store_data']);
//     Route::post('/project/update/{id}', [ProjectController::class, 'update_data']);
//     Route::delete('/project/delete/{id}', [ProjectController::class, 'delete_data']);

//     // Members
//     Route::post('/member/store', [MemberController::class, 'store_data']);
//     Route::post('/member/update/{id}', [MemberController::class, 'update']);
//     Route::delete('/member/delete/{id}', [MemberController::class, 'delete']);

//     // Categories
//     Route::post('/category/store', [CategoryController::class, 'store']);
//     Route::post('/category/update/{id}', [CategoryController::class, 'update']);
//     Route::delete('/category/delete/{id}', [CategoryController::class, 'delete']);

//     // // Blogs
//     Route::post('/blog/store', [BlogController::class, 'store']);
//     Route::post('/blog/update/{id}', [BlogController::class, 'update']);
//     Route::delete('/blog/delete/{id}', [BlogController::class, 'delete']);



// })->name('dashboard');




//  Auth | /api/v1/user name='api.v1.user'
// Route::group(['prefix' => 'user', 'middleware' => 'auth:sanctum'], function () {

//     Route::get('/hello', function () {
//         return response()->json(['message' => 'Hello, World!']);
//     });
// })->name('user');


