<?php

use Illuminate\Support\Facades\Route;
use TheRiptide\LaravelDynamicText\Http\Livewire\TextIndex;
use TheRiptide\LaravelDynamicText\Middleware\AuthorizeDashboardMiddleware;

Route::redirect('dashboard', config('dyndash.dash_home') ?? 'dashboard/article')->name('dyndash.home');

Route::get('dashboard/texts/edit', TextIndex::class )->name('dashboard_texts')->middleware(['web', AuthorizeDashboardMiddleware::class]);
