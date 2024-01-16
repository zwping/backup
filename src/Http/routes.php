<?php

use Encore\Admin\Backup\Http\Controllers;
use Illuminate\Support\Facades\Route;

Route::get('backup', Controllers\BackupController::class.'@index');