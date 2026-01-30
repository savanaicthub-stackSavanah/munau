<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WebController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\StudentPortalController;
use App\Http\Controllers\AdmissionController;

// Public Website Routes
Route::get('/', [WebController::class, 'home'])->name('home');
Route::get('/about', [WebController::class, 'about'])->name('about');
Route::get('/programs', [WebController::class, 'programs'])->name('programs');
Route::get('/programs/{id}', [WebController::class, 'programDetail'])->name('programs.show');
Route::get('/departments', [WebController::class, 'departments'])->name('departments');
Route::get('/departments/{id}', [WebController::class, 'departmentDetail'])->name('departments.show');
Route::get('/admission-requirements', [WebController::class, 'admissionRequirements'])->name('admission-requirements');
Route::get('/news', [WebController::class, 'news'])->name('news');
Route::get('/news/{slug}', [WebController::class, 'newsDetail'])->name('news.show');
Route::get('/events', [WebController::class, 'events'])->name('events');
Route::get('/events/{slug}', [WebController::class, 'eventDetail'])->name('events.show');
Route::get('/gallery', [WebController::class, 'gallery'])->name('gallery');
Route::get('/gallery/{slug}', [WebController::class, 'galleryAlbum'])->name('gallery.show');
Route::get('/management', [WebController::class, 'management'])->name('management');
Route::get('/downloads', [WebController::class, 'downloads'])->name('downloads');
Route::get('/contact', [WebController::class, 'contact'])->name('contact');
Route::post('/contact', [WebController::class, 'contactSubmit'])->name('contact.submit');
Route::get('/search', [WebController::class, 'search'])->name('search');

// Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/login', [AuthController::class, 'authenticate'])->name('authenticate');
    Route::get('/register', [AuthController::class, 'register'])->name('register');
    Route::post('/register', [AuthController::class, 'store'])->name('register.store');
    Route::get('/forgot-password', [AuthController::class, 'forgotPassword'])->name('password.request');
    Route::post('/forgot-password', [AuthController::class, 'sendResetLink'])->name('password.email');
    Route::get('/reset-password/{token}', [AuthController::class, 'resetPassword'])->name('password.reset');
    Route::post('/reset-password', [AuthController::class, 'updatePassword'])->name('password.update');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Admission Application Routes
    Route::prefix('admission')->group(function () {
        Route::get('/apply', [AdmissionController::class, 'apply'])->name('admission.apply');
        Route::post('/apply', [AdmissionController::class, 'store'])->name('admission.store');
        Route::get('/application/{id}', [AdmissionController::class, 'show'])->name('admission.show');
        Route::put('/application/{id}', [AdmissionController::class, 'update'])->name('admission.update');
        Route::post('/application/{id}/submit', [AdmissionController::class, 'submit'])->name('admission.submit');
        Route::post('/application/{id}/upload-document', [AdmissionController::class, 'uploadDocument'])->name('admission.upload-document');
        Route::post('/application/{id}/pay-fee', [AdmissionController::class, 'payAdmissionFee'])->name('admission.pay-fee');
        Route::get('/tracking', [AdmissionController::class, 'tracking'])->name('admission.tracking');
    });

    // Student Portal Routes
    Route::prefix('student')->middleware('student')->group(function () {
        Route::get('/dashboard', [StudentPortalController::class, 'dashboard'])->name('student.dashboard');
        Route::get('/profile', [StudentPortalController::class, 'profile'])->name('student.profile');
        Route::put('/profile', [StudentPortalController::class, 'updateProfile'])->name('student.profile.update');
        
        // Academics
        Route::get('/courses', [StudentPortalController::class, 'courses'])->name('student.courses');
        Route::post('/courses/{id}/register', [StudentPortalController::class, 'registerCourse'])->name('student.course.register');
        Route::post('/courses/{id}/drop', [StudentPortalController::class, 'dropCourse'])->name('student.course.drop');
        
        Route::get('/timetable', [StudentPortalController::class, 'timetable'])->name('student.timetable');
        Route::get('/exam-schedule', [StudentPortalController::class, 'examSchedule'])->name('student.exam-schedule');
        Route::get('/results', [StudentPortalController::class, 'results'])->name('student.results');
        Route::get('/transcript', [StudentPortalController::class, 'transcript'])->name('student.transcript');
        
        // Finance
        Route::get('/finances', [StudentPortalController::class, 'finances'])->name('student.finances');
        Route::get('/fees', [StudentPortalController::class, 'fees'])->name('student.fees');
        Route::post('/fees/{id}/pay', [StudentPortalController::class, 'payFee'])->name('student.fee.pay');
        Route::get('/receipts', [StudentPortalController::class, 'receipts'])->name('student.receipts');
        Route::get('/receipt/{id}/download', [StudentPortalController::class, 'downloadReceipt'])->name('student.receipt.download');
        
        // Hostel
        Route::get('/hostel', [StudentPortalController::class, 'hostel'])->name('student.hostel');
        Route::post('/hostel/apply', [StudentPortalController::class, 'applyHostel'])->name('student.hostel.apply');
        
        // ID Card
        Route::get('/id-card', [StudentPortalController::class, 'idCard'])->name('student.id-card');
        Route::post('/id-card/request', [StudentPortalController::class, 'requestIdCard'])->name('student.id-card.request');
        
        // Notifications
        Route::get('/notifications', [StudentPortalController::class, 'notifications'])->name('student.notifications');
        Route::post('/notifications/{id}/read', [StudentPortalController::class, 'markNotificationRead'])->name('student.notification.read');
    });
});

// Admin Routes (can be in separate admin.php route file)
Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', 'App\Http\Controllers\Admin\DashboardController@index')->name('admin.dashboard');
    Route::resource('users', 'App\Http\Controllers\Admin\UserController');
    Route::resource('departments', 'App\Http\Controllers\Admin\DepartmentController');
    Route::resource('programs', 'App\Http\Controllers\Admin\ProgramController');
    Route::resource('courses', 'App\Http\Controllers\Admin\CourseController');
    Route::resource('admissions', 'App\Http\Controllers\Admin\AdmissionController');
    Route::resource('students', 'App\Http\Controllers\Admin\StudentController');
    Route::resource('finances', 'App\Http\Controllers\Admin\FinanceController');
});
