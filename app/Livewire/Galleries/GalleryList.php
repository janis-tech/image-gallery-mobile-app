<?php

namespace App\Livewire\Galleries;

use App\Services\ImageGalleryHttp\ImageGalleryHttpServiceInterface;
use Livewire\Attributes\On;
use Livewire\Component;

class GalleryList extends Component
{
    /**
     * @var array<int, array<string, mixed>>
     */
    public array $galleries = [];


    /**
     * The array representation of pagination data for Livewire compatibility
     */
    public array $pagination = [];

    public int $current_page = 1;

    public int $per_page = 15;

    public ?string $search = null;

    protected $query_string = [
        'search' => ['except' => ''],
        'current_page' => ['except' => 1, 'as' => 'page'],
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
    public function handlePageChange($page, $page_name = 'page')
    {
        if ($page_name === 'page') {
            $this->current_page = $page;
            $this->refreshGalleries();
        }
    }

    public function resetPage()
    {
        $this->current_page = 1;
    }

    public function refreshGalleries()
    {
        $result = $this->imageGalleryHttpService->getGalleries(
            page: $this->current_page,
            per_page: $this->per_page,
            search: $this->search
        );

        $data_array = $result->toArray();
        $this->galleries = $data_array['data'];
        $this->pagination =$data_array['pagination'];
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
