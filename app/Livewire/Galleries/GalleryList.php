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

    public function updated($attribute, $value)
    {
        if ($attribute == 'search') {
            $this->page = 1;
        }
        if (in_array($attribute, ['page', 'search'])) {
            $this->refreshGalleries();
        }
    }

    public function refreshGalleries()
    {
        $this->galleries = $this->imageGalleryHttpService->getGalleries(
            page: $this->page,
            per_page: $this->per_page,
            search: $this->search
        );
    }

    public function deleteGallery($gallery_id)
    {
        $result = $this->imageGalleryHttpService->deleteGallery($gallery_id);

        if ($result) {
            session()->flash('message', 'Gallery deleted successfully!');
            $this->refreshGalleries();
        } else {
            session()->flash('error', 'Failed to delete gallery. Please try again.');
        }
    }

    public function render()
    {
        return view('livewire.galleries.gallery-list');
    }
}
