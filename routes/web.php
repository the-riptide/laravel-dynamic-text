<?php

use Illuminate\Support\Facades\Route;
use TheRiptide\LaravelDynamicText\Http\Livewire\TextIndex;
use TheRiptide\LaravelDynamicText\Middleware\AuthorizeDashboardMiddleware;

Route::get('dashboard/texts', TextIndex::class )->name('dashboard_texts')->middleware(['web', AuthorizeDashboardMiddleware::class]);
