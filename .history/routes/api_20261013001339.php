<?php

use App\Http\Controllers\Api\PendaftaranController;
use Illuminate\Support\Facades\Route;

Route::get('/pendaftaran', [PendaftaranController::class, 'index']);
