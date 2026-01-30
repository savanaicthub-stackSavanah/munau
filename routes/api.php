<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\PaymentController;
use App\Http\Controllers\Api\StudentController;
use App\Http\Controllers\Api\AdmissionController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->group(function () {
    // Student Profile
    Route::get('/student/profile', [StudentController::class, 'getProfile']);
    Route::put('/student/profile', [StudentController::class, 'updateProfile']);
    
    // Course Enrollment
    Route::get('/student/courses', [StudentController::class, 'getCourses']);
    Route::post('/student/courses/register', [StudentController::class, 'registerCourses']);
    Route::get('/student/timetable', [StudentController::class, 'getTimetable']);
    
    // Results
    Route::get('/student/results', [StudentController::class, 'getResults']);
    Route::get('/student/transcript', [StudentController::class, 'getTranscript']);
    
    // Payments
    Route::get('/finance/fees', [PaymentController::class, 'getFees']);
    Route::post('/finance/payment/initiate', [PaymentController::class, 'initiatePayment']);
    Route::get('/finance/payment/{id}/status', [PaymentController::class, 'getPaymentStatus']);
    Route::post('/finance/payment/{id}/verify', [PaymentController::class, 'verifyPayment']);
    
    // ID Card
    Route::get('/student/id-card', [StudentController::class, 'getIDCard']);
    Route::post('/student/id-card/request', [StudentController::class, 'requestIDCard']);
    
    // Hostel
    Route::get('/student/hostel', [StudentController::class, 'getHostelInfo']);
    Route::post('/student/hostel/apply', [StudentController::class, 'applyHostel']);
    Route::post('/student/hostel/checkin', [StudentController::class, 'hostelCheckIn']);
    
    // Notifications
    Route::get('/notifications', [StudentController::class, 'getNotifications']);
    Route::put('/notifications/{id}/read', [StudentController::class, 'markNotificationRead']);
});

// Public API Routes (for payment gateways, webhooks)
Route::post('/webhook/payment/paystack', [PaymentController::class, 'paystackWebhook']);
Route::post('/webhook/payment/flutterwave', [PaymentController::class, 'flutterwaveWebhook']);

// Admission API
Route::post('/admission/apply', [AdmissionController::class, 'submitApplication']);
Route::get('/admission/requirements', [AdmissionController::class, 'getRequirements']);
Route::get('/admission/programs', [AdmissionController::class, 'getPrograms']);

// Public data endpoints
Route::get('/public/programs', [StudentController::class, 'getPublicPrograms']);
Route::get('/public/departments', [StudentController::class, 'getPublicDepartments']);
Route::get('/public/news', [StudentController::class, 'getPublicNews']);
Route::get('/public/events', [StudentController::class, 'getPublicEvents']);
