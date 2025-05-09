<?php

namespace App\Livewire\Galleries;

use App\Services\ImageGalleryHttp\ImageGalleryHttpServiceInterface;
use Livewire\Attributes\On;
use Livewire\Component;

class GalleryShow extends Component
{
    private ImageGalleryHttpServiceInterface $imageGalleryHttpService;

    /**
     * @var array<string, string>
     */
    public array $gallery;

    /**
     * @var array<array<string, mixed>>
     */
    public array $images = [];

    /**
     * @var array<mixed>
     */
    public array $pagination;

    public string $gallery_id = '';

    public string $search = '';

    public int $per_page = 12;

    public int $current_page = 1;

    /**
     * @var array<string, mixed>
     */
    protected array $query_string = [
        'search' => ['except' => ''],
        'current_page' => ['except' => 1, 'as' => 'page'],
    ];

    public function boot(): void
    {
        $this->imageGalleryHttpService = app(ImageGalleryHttpServiceInterface::class);
    }

    public function mount(string $id): void
    {
        $this->gallery_id = $id;
        $dto = $this->imageGalleryHttpService->getGallery($this->gallery_id);
        $this->gallery = $dto->toArray();
        $this->refreshImages();
    }

    public function render(): \Illuminate\Contracts\View\View
    {
        return view('livewire.galleries.gallery-show');
    }

    public function refreshImages(): void
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
    public function handlePageChange(int $page, string $page_name = 'page'): void
    {
        if ($page_name === 'page') {
            $this->current_page = $page;
            $this->refreshImages();
        }
    }

    public function updated(string $attribute): void
    {
        if ($attribute === 'search') {
            $this->resetPage();
            $this->refreshImages();
        }
    }

    public function resetPage(): void
    {
        $this->current_page = 1;
    }

    public function deleteImage(string $image_id): void
    {
        $result = $this->imageGalleryHttpService->deleteGalleryImage($this->gallery_id, $image_id);

        if ($result) {
            session()->flash('message', 'Image deleted successfully!');
        } else {
            session()->flash('error', 'Failed to delete image. Please try again.');
        }

        $this->refreshImages();
    }

    public function uploadImages(): void
    {
        // Implementation will be added later
    }
}
