<?php

use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\ChatController;
// use App\Http\Controllers\ChatController as ControllersChatController;
use App\Http\Controllers\ClassManagementController;
use App\Http\Controllers\CourseAssessmentController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EnrollmentController;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\LearningManagementController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\SchedullingController;
use App\Http\Controllers\StudentClassController;
use Illuminate\Support\Facades\Route;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

Route::get('/import-sql', function () {
    $path = base_path('database/eLearning2.sql'); // Path to your file
    $sql = File::get($path);

    // This runs the entire SQL file
    DB::unprepared($sql);

    return "Database imported successfully!";
});
Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/dashboard/Admin', [DashboardController::class, 'index'])->name('dashboard.admin');
Route::get('/dashboard/tutor', [DashboardController::class, 'indexTutor'])->name('dashboard.tutor');
Route::get('/dashboard/student', [DashboardController::class, 'indexStudent'])->name('dashboard.student');

Route::middleware('auth')->group(function () {


    ##Approval Entry
    Route::prefix('application')->name('application.')->group(function(){
        Route::get('/index', [ApplicationController::class,'index'])->name('index');
        Route::get('/index/tutor', [ApplicationController::class,'indexTutor'])->name('indexTutor');
        Route::get('/tutor-application/{id}', [ApplicationController::class,'showTutorApplication'])->name('show');
        Route::get('/approve/{id}', [ApplicationController::class, 'approveStudent'])->name('student.approve');
        Route::get('/reject/{id}', [ApplicationController::class, 'rejectStudent'])->name('student.reject');
        Route::get('/approve/tutor/{id}', [ApplicationController::class, 'approveTutor'])->name('tutor.approve');
        Route::get('/reject/tutor/{id}', [ApplicationController::class, 'rejectTutor'])->name('tutor.reject');
    });

    ##Approval Enrollment
    Route::prefix('enrollment')->name('enrollment.')->group(function(){
        Route::get('/admin/enrollments', [ApplicationController::class, 'indexEnrollment'])->name('admin.index');
        Route::post('/admin/enrollments/approve/{id}', [ApplicationController::class, 'approveEnrollment'])->name('admin.approve');
        Route::post('/admin/enrollments/reject/{id}', [ApplicationController::class, 'rejectEnrollment'])->name('admin.reject');
        Route::post('/admin/enrollments/approve-change/{id}', [EnrollmentController::class, 'approveChange'])->name('admin.approveChange');
        Route::get('/admin/tutor-changes', [ApplicationController::class, 'indexTutorChanges'])->name('admin.tutorChanges');
    });

    ##Class Management
    Route::prefix('class')->name('class.')->group(function(){
        Route::get('/index', [ClassManagementController::class,'index'])->name('index');
        Route::get('/create', [ClassManagementController::class,'create'])->name('create');
        Route::post('/store', [ClassManagementController::class, 'store'])->name('store');
        Route::post('/update/{id}', [ClassManagementController::class,'update'])->name('update');
        Route::get('/edit/{id}', [ClassManagementController::class,'edit'])->name('edit');
        Route::get('/delete/{id}', [ClassManagementController::class,'destroy'])->name('destroy');
        Route::get('/show/{id}', [ClassManagementController::class,'show'])->name('show');
    });

    ##Learning Management
    Route::prefix('learning-material')->name('learning-material.')->group(function(){
        Route::get('/index/{class_id}', [LearningManagementController::class, 'index'])->name('index');
        Route::get('/create/{class_id}', [LearningManagementController::class, 'create'])->name('create');
        Route::post('/store', [LearningManagementController::class, 'store'])->name('store');
        Route::post('/upload', [LearningManagementController::class, 'upload'])->name('upload');
        Route::delete('/revert', [LearningManagementController::class, 'revert'])->name('revert');
        Route::get('/destroy/{id}', [LearningManagementController::class, 'destroy'])->name('destroy');
        Route::get('/learning-material/download/{id}/{type}', [LearningManagementController::class, 'download'])->name('download');
        Route::post('/update/{id}', [LearningManagementController::class,'update'])->name('update');
        Route::get('/edit/{id}', [LearningManagementController::class,'edit'])->name('edit');
        // Route::get('/delete/{id}', [LearningManagementController::class,'destroy'])->name('destroy');
        Route::get('/open-file/{id}/{nama}', [LearningManagementController::class, 'openFile'])->name('open-file');
        Route::get('/learning-material/delete-file/{id}/{type}', [LearningManagementController::class, 'deleteSingleFile'])->name('file-delete');

    });

    ##Enrollment
    Route::prefix('enrollment')->name('enrollment.')->group(function(){
        Route::get('/index', [EnrollmentController::class, 'index'])->name('index');
        Route::get('/tutors/{class_id}/{recommended_tutor_id?}', [EnrollmentController::class, 'tutorSelection'])->name('tutors');
        Route::get('/tutor-profile/{class_id}/{tutor_id}', [EnrollmentController::class, 'tutorProfile'])->name('tutor-profile');
        Route::post('/store', [EnrollmentController::class, 'store'])->name('store');
        Route::get('/enrollment/change-tutor/{class_id}', [EnrollmentController::class, 'changeTutorSelection'])->name('changeTutor');
    });

    ##List of Class
    Route::prefix('student')->name('student.')->group(function(){
        Route::get('/my-classes', [StudentClassController::class, 'index'])->name('class.index');
        Route::get('/class/{class_id}/materials', [StudentClassController::class, 'showMaterials'])->name('class.materials');
        Route::get('/learning-material/download/{id}/{type}', [StudentClassController::class, 'download'])->name('learning-material.download');

    });

    ##Announcement
    Route::prefix('announcement')->name('announcement.')->group(function () {
        Route::get('/index', [AnnouncementController::class,'index'])->name('index');
        Route::get('/create', [AnnouncementController::class,'create'])->name('create');
        Route::post('/store', [AnnouncementController::class,'store'])->name('store');
        Route::post('/update/{id}', [AnnouncementController::class,'update'])->name('update');
        Route::get('/edit/{id}', [AnnouncementController::class,'edit'])->name('edit');
        Route::get('/delete/{id}', [AnnouncementController::class,'destroy'])->name('destroy');
        Route::get('/announcement/show/{id}', [AnnouncementController::class, 'show']);

    });

    ##Quiz
    Route::prefix('quiz')->name('quiz.')->group(function () {
        Route::get('/tutor/create/{learning_material_id}', [QuizController::class, 'create'])->name('create');
        Route::post('/tutor/store', [QuizController::class, 'store'])->name('store');
        Route::get('/student/play/{quiz_id}', [QuizController::class, 'play'])->name('play');
        Route::post('/student/submit/{quiz_id}', [QuizController::class, 'submit'])->name('submit');
        Route::get('map/{class_id}', [QuizController::class, 'showMap'])->name('map');
        Route::get('/review/{quiz_id}', [QuizController::class, 'review'])->name('review');
    });

    ##Payment
    Route::prefix('payment')->name('payment.')->group(function () {
        Route::get('/index', [PaymentController::class, 'index'])->name('index');
        Route::post('/bills', [PaymentController::class, 'generateAllMonthlyBills'])->name('generate-all-bills');
        Route::post('/bills/{class_id}', [PaymentController::class, 'generateMonthlyBill'])->name('generate-single-bill');

    });

    ##Feedback
    Route::prefix('feedback')->name('feedback.')->group(function () {
        Route::get('/index/{class_id}', [FeedbackController::class, 'index'])->name('index');
        Route::post('/feedback/store', [FeedbackController::class, 'store'])->name('store');
        Route::get('/show', [FeedbackController::class, 'showAdmin'])->name('show-admin');
        Route::get('/show/tutor', [FeedbackController::class, 'showTutor'])->name('show-tutor');

    });

    // Route::prefix('course_assesment')->name('course_assesment.')->group(function () {
    //     Route::get('/index/{class_id}', [CourseAssessmentController::class, 'index'])->name('index');
    //     Route::post('/store', [CourseAssessmentController::class, 'store'])->name('store');
    //     Route::get('/show', [CourseAssessmentController::class, 'showAdmin'])->name('show-admin');
    //     Route::get('/show/tutor', [CourseAssessmentController::class, 'showTutor'])->name('show-tutor');

    // });

    ##Chat
    Route::prefix('chat')->name('chat.')->group(function () {
        Route::get('/', [ChatController::class, 'index'])->name('index');
        Route::get('/{receiver_id}', [ChatController::class, 'show'])->name('show');
        Route::post('/send', [ChatController::class, 'store'])->name('store');
        Route::post('/reschedule/approve', [SchedullingController::class, 'approveReschedule'])->name('reschedule.approve');
        Route::post('/reschedule/reject', [SchedullingController::class, 'rejectReschedule'])->name('reschedule.reject');


    });

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/tutor-profile/{tutor_id}', [ApplicationController::class, 'tutorProfile'])->name('tutor-profile');



});

require __DIR__.'/auth.php';
