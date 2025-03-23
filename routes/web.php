<?php

use App\Http\Controllers\Admin\ApplicantController;
use App\Http\Controllers\Admin\BlogController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\HomeController;
use App\Http\Controllers\Admin\JobController;
use App\Http\Controllers\Admin\LocationController;
use App\Http\Controllers\Admin\LoginController;
use App\Http\Controllers\Admin\ManagementController;
use App\Http\Controllers\Admin\MemberController;
use App\Http\Controllers\Admin\OurStoryController;
use App\Http\Controllers\Admin\PopupController;
use App\Http\Controllers\Admin\ProjectController;
use App\Http\Controllers\Admin\FileUploadControler;

use App\Http\Controllers\Admin\ProjectStatusController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\SliderController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

// Route::get('/', function () {

//     return 'Welcome,';

// });



Route::get('/login', [LoginController::class, 'index'])->name("login.index");
Route::post('/login/submit', [LoginController::class, 'login'])->name("login.login");
Route::post("/logout", [LoginController::class,"logout"])->name('logout');

Route::middleware(['admin'])->name('admin.')->prefix('admin')->group(function () {

    //Dashboard Start =================================================================
    Route::get("/dashboard",[DashboardController::class,"index"])->name("dashboard.index");
    //Dashboard END ===================================================================


    //Categories start =================================================================
    Route::get("/categories",[CategoryController::class,"index"])->name('category.list');
    Route::get("/category/add",[CategoryController::class,"add"])->name('category.add');
    Route::post("/category/store",[CategoryController::class,"store"])->name('category.store');
    Route::get("/category/edit/{id}",[CategoryController::class,"edit"])->name('category.edit');
    Route::put("/category/update/{id}",[CategoryController::class,"update"])->name('category.update');
    Route::delete("/category/delete/{id}",[CategoryController::class,"delete"])->name('category.delete');
    //Categories End =================================================================


    //Blogs Start =================================================================
    Route::get("/blogs",[BlogController::class,"index"])->name('blog.list');
    Route::get("/blog/add",[BlogController::class,"add"])->name('blog.add');
    Route::post("/blog/add",[BlogController::class,"store"])->name('blog.store');
    Route::get("/blog/edit/{id}",[BlogController::class,"edit"])->name('blog.edit');
    Route::put("/blog/update/{id}",[BlogController::class,"update"])->name('blog.update');
    Route::delete("/blog/delete/{id}",[BlogController::class,"delete"])->name('blog.delete');
    Route::post("/upload/files",[BlogController::class,"storeFiles"])->name("blog.upload.files");
    //Blogs End =================================================================

    //Members Start =================================================================
    Route::get("/members",[MemberController::class,"index"])->name('member.list');
    Route::get("/member/add",[MemberController::class,"add"])->name("member.add");
    Route::post("/member/add",[MemberController::class,"store"])->name("member.store");
    Route::get("/member/edit/{id}",[MemberController::class,"edit"])->name("member.edit");
    Route::put("/member/update/{id}",[MemberController::class,"update"])->name("member.update");
    Route::delete("/member/delete/{id}",[MemberController::class,"delete"])->name("member.delete");
    //Members End =================================================================


    //fileuploads Start =================================================================
    Route::get("/fileuploads",[FileUploadControler::class,"index"])->name('fileupload.list');
    Route::get("/fileuploads/add",[FileUploadControler::class,"add"])->name("fileupload.add");
    Route::post("/fileuploads/add",[FileUploadControler::class,"store"])->name("fileupload.store");
    Route::get("/fileuploads/edit/{id}",[FileUploadControler::class,"edit"])->name("fileupload.edit");
    Route::put("/fileuploads/update/{id}",[FileUploadControler::class,"update"])->name("fileupload.update");
    Route::delete("/fileuploads/delete/{id}",[FileUploadControler::class,"delete"])->name("fileupload.delete");
    Route::post("/fileuploads/bulk-delete/",[FileUploadControler::class,"bulkDelete"])->name("fileupload.bulk.delete");
    //fileuploads End =================================================================

    //Locations Start =================================================================
    Route::get("/locations",[LocationController::class,"index"])->name('location.list');
    Route::get("/locations/add",[LocationController::class,"add"])->name("location.add");
    Route::post("/locations/add",[LocationController::class,"store"])->name("location.store");
    Route::get("/locations/edit/{id}",[LocationController::class,"edit"])->name("location.edit");
    Route::put("/locations/update/{id}",[LocationController::class,"update"])->name("location.update");
    Route::delete("/locations/delete/{id}",[LocationController::class,"delete"])->name("location.delete");
    //Locations End =================================================================

    //Project Statuses Start =================================================================
    Route::get("/project-status",[ProjectStatusController::class,"index"])->name('project_status.list');
    Route::get("/project-status/add",[ProjectStatusController::class,"add"])->name("project_status.add");
    Route::post("/project-status/add",[ProjectStatusController::class,"store"])->name("project_status.store");
    Route::get("/project-status/edit/{id}",[ProjectStatusController::class,"edit"])->name("project_status.edit");
    Route::put("/project-status/update/{id}",[ProjectStatusController::class,"update"])->name("project_status.update");
    Route::delete("/project-status/delete/{id}",[ProjectStatusController::class,"delete"])->name("project_status.delete");
    //Project Statuses End =================================================================

    //Members Start =================================================================
    Route::get("/management",[ManagementController::class,"index"])->name('management.list');
    Route::get("/management/add",[ManagementController::class,"add"])->name("management.add");
    Route::post("/management/add",[ManagementController::class,"store"])->name("management.store");
    Route::get("/management/edit/{id}",[ManagementController::class,"edit"])->name("management.edit");
    Route::put("/management/update/{id}",[ManagementController::class,"update"])->name("management.update");
    Route::delete("/management/delete/{id}",[ManagementController::class,"delete"])->name("management.delete");
    //Members End =================================================================

    //Projects Start =================================================================
    Route::get("/projects",[ProjectController::class,"index"])->name("project.list");
    Route::get("/project/add",[ProjectController::class,'add'])->name("project.add");
    Route::post("/project/add",[ProjectController::class,'store'])->name("project.store");
    Route::get("/project/edit/{id}",[ProjectController::class,'edit'])->name("project.edit");
    Route::put("/project/update/{id}",[ProjectController::class,'update'])->name("project.update");
    Route::delete("/project/delete/{id}",[ProjectController::class,'delete'])->name("project.delete");
    //Projects End =================================================================

    //Settings Start =================================================================
    Route::get("/settings",[SettingController::class,"index"])->name("settings.list");
    Route::get("/settings/add",[SettingController::class,'add'])->name("settings.add");
    Route::post("/settings/add",[SettingController::class,'store'])->name("settings.store");
    Route::put("/settings/update/{id}",[SettingController::class,'update'])->name("settings.update");
    Route::delete("/settings/delete/{id}",[SettingController::class,'delete'])->name("settings.delete");
    //Settings End =================================================================

    //User Setting / Admin Setting Start =================================================================
    Route::get("/user-settings",[UserController::class,"index"])->name("user.index");
    Route::put("/user-settings/update",[UserController::class,"update"])->name("user.update");
    //User Setting / Admin Setting End =================================================================

    //Home Start =================================================================

    //Slider
    Route::get("/home/sliders",[HomeController::class, "sliderIndex"])->name("home.slider.list");
    Route::get("/home/sliders/add",[HomeController::class,"sliderAdd"])->name("home.slider.add");
    Route::post("/home/sliders/add",[HomeController::class,"sliderStore"])->name("home.slider.store");
    Route::get("/home/sliders/edit/{id}",[HomeController::class,"sliderEdit"])->name("home.slider.edit");
    Route::put("/home/sliders/update/{id}",[HomeController::class,"sliderUpdate"])->name("home.slider.update");
    Route::delete("/home/sliders/delete/{id}",[HomeController::class,"sliderDelete"])->name("home.slider.delete");

    //explore
    Route::get("/home/explores",[HomeController::class, "exploreIndex"])->name("home.explore.list");
    Route::get("/home/explores/add",[HomeController::class,"exploreAdd"])->name("home.explore.add");
    Route::post("/home/explores/add",[HomeController::class,"exploreStore"])->name("home.explore.store");
    Route::get("/home/explores/edit/{id}",[HomeController::class,"exploreEdit"])->name("home.explore.edit");
    Route::put("/home/explores/update/{id}",[HomeController::class,"exploreUpdate"])->name("home.explore.update");
    Route::delete("/home/explores/delete/{id}",[HomeController::class,"exploreDelete"])->name("home.explore.delete");

    //Stats
    Route::get("/home/stats",[HomeController::class, "statsEdit"])->name("home.stats.edit");
    Route::put("/home/stats/update/{id}",[HomeController::class,"statsUpdate"])->name("home.stats.update");



    //Home End =================================================================

    //Our Story Start =================================================================
    Route::get("/ourstory",[OurStoryController::class, "ourStoryEdit"])->name("ourstory.edit");
    Route::put("/ourstory/update/{id}",[OurStoryController::class,"ourStoryUpdate"])->name("ourstory.update");
    //Our Story End =================================================================
    //Why salmon Start =================================================================
        //Slider
        Route::get("/sliders",[SliderController::class, "index"])->name("slider.list");
        Route::get("/sliders/add",[SliderController::class,"add"])->name("slider.add");
        Route::post("/sliders/add",[SliderController::class,"store"])->name("slider.store");
        Route::get("/sliders/edit/{id}",[SliderController::class,"edit"])->name("slider.edit");
        Route::put("/sliders/update/{id}",[SliderController::class,"update"])->name("slider.update");
        Route::delete("/sliders/delete/{id}",[SliderController::class,"delete"])->name("slider.delete");
    //Why salmon end =================================================================

    //Career Jobs Start =================================================================
    Route::get("/jobs/applicants",[ApplicantController::class, "index"])->name("job.applicant.list");
    Route::get("/jobs/applicants/add",[ApplicantController::class,"add"])->name("job.applicant.add");
    Route::post("/jobs/applicants/add",[ApplicantController::class,"store"])->name("job.applicant.store");
    Route::get("/jobs/applicants/edit/{id}",[ApplicantController::class,"edit"])->name("job.applicant.edit");
    Route::put("/jobs/applicants/update/{id}",[ApplicantController::class,"update"])->name("job.applicant.update");
    Route::delete("/jobs/applicants/delete/{id}",[ApplicantController::class,"delete"])->name("job.applicant.delete");
    //Career Jobs End =================================================================

    //Career Jobs Start =================================================================
    Route::get("/jobs",[JobController::class, "index"])->name("job.list");
    Route::get("/jobs/add",[JobController::class,"add"])->name("job.add");
    Route::post("/jobs/add",[JobController::class,"store"])->name("job.store");
    Route::get("/jobs/edit/{id}",[JobController::class,"edit"])->name("job.edit");
    Route::put("/jobs/update/{id}",[JobController::class,"update"])->name("job.update");
    Route::delete("/jobs/delete/{id}",[JobController::class,"delete"])->name("job.delete");
    //Career Jobs End =================================================================

    //Popup Page Start =================================================================
    Route::get("/popup/edit/{id}",[PopupController::class, "edit"])->name("popup.edit");
    Route::put("/popup/update/{id}",[PopupController::class,"update"])->name("popup.update");

    Route::get("/popups",[PopupController::class, "index"])->name("popup.list");
    Route::get("/popups/add",[PopupController::class,"add"])->name("popup.add");
    Route::post("/popups/add",[PopupController::class,"store"])->name("popup.store");
    Route::delete("/popups/delete/{id}",[PopupController::class,"delete"])->name("popup.delete");
    //Popup Page End =================================================================

});


