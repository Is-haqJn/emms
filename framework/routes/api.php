<?php

use App\Http\Controllers\Api\EquipmentController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CallEntryController;
use App\Http\Controllers\Api\DashboardController;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'loginUser']);
Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/update-password', [AuthController::class, 'updatePassword']);
    Route::post('/profile-pic-update',[AuthController::class,'profile_pic_update']);
    Route::controller(EquipmentController::class)->group(function () {
    Route::post('/edit-equipment','edit_equipment');
    Route::post('/equipments-details','equipment');
    Route::post('/equipment','single_equipment');
    Route::post('/equipment/user/list','equipment_user_list');
    Route::post('/equipment-history','equipment_history');
    Route::post('/store-equipment','equipment_store');
    Route::post('/call-entries','call_entries');
    Route::post('/qr','qr_link');
    Route::post('/departments','department');
    Route::post('/hospitals','hospital');
});
Route::controller(CallEntryController::class)->group(function () {
    Route::post('/equipment-maintenance', 'equipment_maintenance');
    Route::post('/call-entries/attend-call','attend_call');
    });
});
Route::post('dashboard-count',[DashboardController::class,'dashboardCount']);


