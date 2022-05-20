<?php

use Illuminate\Support\Facades\Route;
use TheRiptide\LaravelDynamicText\Http\Livewire\TextIndex;

Route::get('dashboard/texts', TextIndex::class )->middleware(['web']);

// Route::get('/dashboard/create/{type}', DashboardManage::class)->name('dyndash.create')->middleware(['web', AuthorizeDashboardMiddleware::class]);
// Route::get('/dashboard/edit/{type}/{id}', DashboardManage::class)->name('dyndash.edit')->middleware(['web', AuthorizeDashboardMiddleware::class]);
// Route::get('/dashboard/{type}', DashboardIndex::class)->name('dyndash.index')->middleware(['web', AuthorizeDashboardMiddleware::class]);
