<?php

use App\Livewire\Galleries\GalleryCreate;
use App\Livewire\Galleries\GalleryEdit;
use App\Livewire\Galleries\GalleryList;
use App\Livewire\Galleries\GalleryShow;
use App\Livewire\Galleries\Images\GalleryImageEdit;
use App\Livewire\Galleries\Images\GalleryImageShow;
use App\Livewire\Galleries\Images\GalleryImageUpload;
use App\Livewire\Settings\Appearance;
use App\Livewire\Settings\ImageGalleryIntegration;
use App\Livewire\Settings\Password;
use App\Livewire\Settings\Profile;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (auth()->check()) {
        return redirect('galleries');
    }

    return redirect('login');
})->name('home');

Route::get('dashboard', function () {
    return redirect('galleries');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Route::get('settings/profile', Profile::class)->name('settings.profile');
    Route::get('settings/password', Password::class)->name('settings.password');
    Route::get('settings/appearance', Appearance::class)->name('settings.appearance');
    Route::get('settings/image-gallery-integration', ImageGalleryIntegration::class)->name('settings.image-gallery-integration');

    Route::get('galleries/create', GalleryCreate::class)->name('galleries.create');
    Route::get('galleries', GalleryList::class)->name('galleries.list');
    Route::get('galleries/{id}', GalleryShow::class)->name('galleries.show');
    Route::get('galleries/{id}/edit', GalleryEdit::class)->name('galleries.edit');

    Route::get('galleries/{gallery_id}/images/{id}', GalleryImageShow::class)->name('galleries.image.show');
    Route::get('galleries/{gallery_id}/images/{id}/edit', GalleryImageEdit::class)->name('galleries.image.edit');
    Route::get('galleries/{gallery_id}/upload', GalleryImageUpload::class)->name('galleries.image.upload');
});

require __DIR__.'/auth.php';
