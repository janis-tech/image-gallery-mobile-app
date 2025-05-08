<?php

namespace App\Livewire\Galleries;

use App\Services\ImageGalleryHttp\ImageGalleryHttpServiceInterface;
use Livewire\Attributes\On;
use Livewire\Component;

class GalleryShow extends Component
{
    private ImageGalleryHttpServiceInterface $imageGalleryHttpService;

    public array $gallery = [];

    public array $images = [];

    public array $pagination = [];

    public string $gallery_id = '';

    public string $search = '';

    public int $perPage = 12;

    public int $currentPage = 1;

    protected $queryString = [
        'search' => ['except' => ''],
        'currentPage' => ['except' => 1, 'as' => 'page'],
    ];

    public function boot()
    {
        $this->imageGalleryHttpService = app(ImageGalleryHttpServiceInterface::class);
    }

    public function mount($id)
    {
        $this->gallery_id = $id;
        $this->gallery = $this->imageGalleryHttpService->getGallery($this->gallery_id);
        $this->refreshImages();
    }

    public function render()
    {
        return view('livewire.galleries.gallery-show');
    }

    public function refreshImages()
    {
        $result = $this->imageGalleryHttpService->getGalleryImages(
            $this->gallery_id,
            $this->search,
            $this->perPage,
            $this->currentPage
        );

        $this->images = $result['data'] ?? [];
        $this->pagination = $result['pagination'] ?? [];
    }

    #[On('pageChange')]
    public function handlePageChange($page, $pageName = 'page')
    {
        if ($pageName === 'page') {
            $this->currentPage = $page;
            $this->refreshImages();
        }
    }

    public function updated($attribute)
    {
        if ($attribute === 'search') {
            $this->resetPage();
            $this->refreshImages();
        }
    }

    public function resetPage()
    {
        $this->currentPage = 1;
    }

    public function deleteImage($image_id)
    {
        $result = $this->imageGalleryHttpService->deleteGalleryImage($this->gallery_id, $image_id);
        
        if ($result) {
            session()->flash('message', 'Image deleted successfully!');
        } else {
            session()->flash('error', 'Failed to delete image. Please try again.');
        }

        $this->refreshImages();
    }

    public function uploadImages()
    {
        // Implementation will be added later
    }
}
