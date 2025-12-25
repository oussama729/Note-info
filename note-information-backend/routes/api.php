<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\NoteInformationController;

Route::get('/notes', [NoteInformationController::class, 'index']);
Route::get('/notes/{reference}', [NoteInformationController::class, 'show']);
Route::post('/notes', [NoteInformationController::class, 'store']);
