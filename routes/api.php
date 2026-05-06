<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/please/encrypt/this', [DashboardController::class, 'encrypt'])
      ->name('crypt.this');// Encrypt This
Route::get('/notifications', [NotificationController::class, 'index'])
      ->name('get.new.notification');// Get New Notification
Route::get('/read-notifications', [NotificationController::class, 'read'])
      ->name('read.notification');// Mark Notification as Read
Route::post('/editcw', [ManifestHouseController::class, 'updateajax'])
     ->name('edit.house.ajax');
