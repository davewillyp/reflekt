<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Route::view('/', 'welcome');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Volt::route('reflekt/create', 'reflekt-create')
    ->middleware(['auth', 'verified'])
    ->name('reflekt.create');

Volt::route('feed', 'feed')
    ->middleware(['auth', 'verified'])
    ->name('feed');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

require __DIR__.'/auth.php';
