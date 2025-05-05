<?php

namespace App\Livewire\Galleries;

use Livewire\Component;
use App\Services\ImageGalleryHttp\ImageGalleryHttpServiceInterface;
use Livewire\Attributes\On;

class GalleryList extends Component
{
    public array $galleries = [];
    public array $pagination = [];
    
    public int $currentPage = 1;
    public int $perPage = 15;
    public ?string $search = null;

    protected $queryString = [
        'search' => ['except' => ''],
        'currentPage' => ['except' => 1, 'as' => 'page'],
    ];

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
            $this->resetPage();
        }
        if (in_array($attribute, ['search'])) {
            $this->refreshGalleries();
        }
    }

    #[On('pageChange')]
    public function handlePageChange($page, $pageName = 'page')
    {
        if ($pageName === 'page') {
            $this->currentPage = $page;
            $this->refreshGalleries();
        }
    }
    
    public function resetPage()
    {
        $this->currentPage = 1;
    }

    public function refreshGalleries()
    {
        $result = $this->imageGalleryHttpService->getGalleries(
            page: $this->currentPage,
            per_page: $this->perPage,
            search: $this->search
        );
        
        $this->galleries = $result['data'] ?? [];
        $this->pagination = $result['pagination'] ?? [];
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
