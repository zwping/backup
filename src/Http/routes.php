<?php

use Encore\Admin\Backup\Http\Controllers;
use Illuminate\Support\Facades\Route;

Route::get('backup', Controllers\BackupController::class.'@index')->name('backup-list');
Route::get('backup/download', Controllers\BackupController::class.'@download')->name('backup-download');
Route::post('backup/run', Controllers\BackupController::class.'@run')->name('backup-run');
Route::delete('backup/delete', Controllers\BackupController::class.'@delete')->name('backup-delete');
