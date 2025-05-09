<?php

namespace App\Livewire\Galleries;

use App\Services\ImageGalleryHttp\ImageGalleryHttpServiceInterface;
use Livewire\Attributes\On;
use Livewire\Component;

class GalleryShow extends Component
{
    private ImageGalleryHttpServiceInterface $imageGalleryHttpService;

    public array $gallery;

    public array $images = [];

    public array $pagination;

    public string $gallery_id = '';

    public string $search = '';

    public int $per_page = 12;

    public int $current_page = 1;

    protected $query_string = [
        'search' => ['except' => ''],
        'current_page' => ['except' => 1, 'as' => 'page'],
    ];

    public function boot()
    {
        $this->imageGalleryHttpService = app(ImageGalleryHttpServiceInterface::class);
    }

    public function mount($id)
    {
        $this->gallery_id = $id;
        $dto = $this->imageGalleryHttpService->getGallery($this->gallery_id);
        $this->gallery = $dto->toArray();
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
            $this->per_page,
            $this->current_page
        );

        $result_array = $result->toArray();

        $this->images = $result_array['data'] ?? [];
        $this->pagination = $result_array['pagination'];
    }

    #[On('pageChange')]
    public function handlePageChange($page, $page_name = 'page')
    {
        if ($page_name === 'page') {
            $this->current_page = $page;
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
        $this->current_page = 1;
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
