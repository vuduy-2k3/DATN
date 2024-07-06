<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AreaController;
use App\Http\Controllers\FloorController;
use App\Http\Controllers\CameraController;
use App\Http\Controllers\VehicleInformationController;
use App\Http\Controllers\VehicleLogController;
use App\Http\Controllers\TicketController;
Route::get('/', function () {
    return redirect()->route('login');
});
Route::get('quanlyguixe.com/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('quanlyguixe.com/login', [LoginController::class, 'login'])->name('login.post');
Route::get('quanlyguixe.com/logout', [LoginController::class, 'logout'])->name('logout');

// Bảo vệ các route này bằng middleware auth.admin
Route::middleware(['auth.admin'])->group(function () {
    Route::controller(DashboardController::class)->prefix('quanlyguixe.com/dashboard')->group(function () {
        Route::get('', 'index')->name('dashboard');
        Route::get('count-registered-spots', 'countRegisteredSpots')->name('count-registered-spots');
        Route::get('count-unregistered-spots', 'countUnregisteredSpots')->name('count-unregistered-spots');
        Route::get('get-pie-chart-data', 'getPieChartData')->name('get-pie-chart-data');
        Route::get('get-bar-chart-data', 'getBarChartData')->name('get-bar-chart-data');
        Route::get('count-cars-in', 'countCarsIn')->name('count-cars-in');
        Route::get('count-cars-out', 'countCarsOut')->name('count-cars-out');
    });

    Route::controller(AreaController::class)->prefix('quanlyguixe.com/areas')->group(function () {
        Route::get('', 'index')->name('areas');
        Route::get('create', 'create')->name('areas.create');
        Route::post('create', 'save')->name('areas.save');
        Route::get('edit/{id}', 'edit')->name('areas.edit');
        Route::post('edit/{id}', 'update')->name('areas.update');
        Route::get('delete/{id}', 'delete')->name('areas.delete');
        Route::get('search', 'search')->name('areas.search');
    });

    Route::controller(FloorController::class)->prefix('quanlyguixe.com/floors')->group(function () {
        Route::get('', 'index')->name('floors');
        Route::get('create', 'create')->name('floors.create');
        Route::post('save', 'save')->name('floors.save');
        Route::get('edit/{id}', 'edit')->name('floors.edit');
        Route::post('edit/{id}', 'update')->name('floors.update');
        Route::get('delete/{id}', 'delete')->name('floors.delete');
        Route::get('search', 'search')->name('floors.search');
    });
  
    Route::controller(VehicleInformationController::class)->prefix('quanlyguixe.com/vehicleInformations')->group(function () {
        Route::get('', 'index')->name('vehicleInformations');
        Route::get('create', 'create')->name('vehicleInformations.create');
        Route::post('save', 'save')->name('vehicleInformations.save');
        Route::get('edit/{id}', 'edit')->name('vehicleInformations.edit');
        Route::post('edit/{id}', 'update')->name('vehicleInformations.update');
        Route::get('delete/{id}', 'delete')->name('vehicleInformations.delete');
        Route::get('search', 'search')->name('vehicleInformations.search');
        Route::get('get-number-locations/{area_id}', 'getNumberLocations')->name('get.number.locations');
    
    });

    Route::get('quanlyguixe.com/camera', [CameraController::class, 'showCamera'])->name('camera.show');
    Route::post('quanlyguixe.com/capture-and-send-image', [CameraController::class, 'captureAndSendImage'])->name('capture-and-send-image');
    Route::post('quanlyguixe.com/upload-image', [CameraController::class, 'uploadImage'])->name('upload-image');
    Route::get('/camera/areas', [CameraController::class, 'getAreas'])->name('camera.getAreas');
    Route::get('/camera/positions', [CameraController::class, 'getPositions'])->name('camera.getPositions');    


    Route::controller(VehicleLogController::class)->prefix('quanlyguixe.com/vehicleLogs')->group(function () {
        Route::get('', 'index')->name('vehicleLogs');
        Route::get('search', 'search')->name('vehicleLogs.search');
    
    });

    Route::controller(TicketController::class)->prefix('quanlyguixe.com/tickets')->group(function () {
        Route::get('', 'index')->name('tickets');
        Route::get('search', 'search')->name('tickets.search');
        Route::get('export-pdf/{ticketId}','exportPDF')->name('tickets.exportPDF');

    });
});