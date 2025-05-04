<?php

namespace App\Livewire\Galleries;

use Livewire\Component;
use App\Services\ImageGalleryHttp\ImageGalleryHttpServiceInterface;

class GalleryShow extends Component
{

    private ImageGalleryHttpServiceInterface $imageGalleryHttpService;

    public array $gallery = [];
    public array $images = [];
    public string $gallery_id = '';
    public string $search = '';

    public function boot()
    {
        $this->imageGalleryHttpService = app(ImageGalleryHttpServiceInterface::class);
    }

    public function mount($id)
    {
        $this->gallery_id = $id;
        $this->gallery = $this->imageGalleryHttpService->getGallery($this->gallery_id);
        $this->images = $this->imageGalleryHttpService->getGalleryImages($this->gallery_id);
    }

    public function render()
    {

        return view('livewire.galleries.gallery-show');
    }

    public function deleteImage($image_id) {

    }

    public function uploadImages()
    {

    }
}
