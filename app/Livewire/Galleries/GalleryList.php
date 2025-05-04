<?php

namespace App\Livewire\Galleries;

use Livewire\Component;
use App\Services\ImageGalleryHttp\ImageGalleryHttpServiceInterface;

class GalleryList extends Component
{
    public $galleries = [];

    public $page = 1;
    public $per_page = 15;
    public $search = null;

    private ImageGalleryHttpServiceInterface $imageGalleryHttpService;

    public function boot()
    {
        $this->imageGalleryHttpService = app(ImageGalleryHttpServiceInterface::class);
    }

    public function mount()
    {
        $this->refreshGalleries();
    }

    public function updatedPage()
    {
        $this->refreshGalleries();
    }

    public function updatedPerPage()
    {
        $this->refreshGalleries();
    }

    public function updatedSearch()
    {
        $this->page = 1;
        $this->refreshGalleries();
    }

    public function refreshGalleries()
    {
        $this->galleries = $this->imageGalleryHttpService->getGalleries(
            page: $this->page,
            per_page: $this->per_page,
            search: $this->search
        );
    }

    public function deleteGallery($galleryId) {}

    public function editGallery($galleryId) {}

    public function render()
    {
        return view('livewire.galleries.gallery-list');
    }
}
