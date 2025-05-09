<?php

namespace App\Livewire\Galleries\Images;

use App\Services\ImageGalleryHttp\ImageGalleryHttpServiceInterface;
use Livewire\Attributes\On;
use Livewire\Component;

class GalleryImageShow extends Component
{
    /**
     * @var array<string, mixed>
     */
    public array $image;

    public bool $show_gallery_view = false;

    public int $current_gallery_index = 0;

    /**
     * @var array<array<string, string>>
     */
    public array $gallery_images = [];

    public bool $current_url_copied = false;

    /**
     * @var array<string, mixed>|null
     */
    public ?array $gallery = null;

    private ImageGalleryHttpServiceInterface $image_gallery_http_service;

    public function boot(): void
    {
        $this->image_gallery_http_service = app(ImageGalleryHttpServiceInterface::class);
    }

    public function mount(string $gallery_id, string $id): void
    {
        $image_dto = $this->image_gallery_http_service->getGalleryImage($gallery_id, $id);
        $gallery_dto = $this->image_gallery_http_service->getGallery($gallery_id);
        $this->image = $image_dto->toArray();
        $this->gallery = $gallery_dto->toArray();
    }

    /**
     * Handle the 'show-image' event to display image details
     *
     * @param array{
     *     id: string,
     *     gallery_id: string,
     *     title: string|null,
     *     description: string|null,
     *     file_name: string|null,
     *     original_filename: string,
     *     alt_text: string|null,
     *     mime_type: string,
     *     file_size: int,
     *     width: int,
     *     height: int,
     *     file_path: string,
     *     file_url: string,
     *     generated_caption: string|null,
     *     presets: array<string, string>,
     *     created_at: string,
     *     updated_at: string
     * } $image_data The complete image data object
     *
     * @return void
     */
    #[On('show-image')]
    public function showImage(array $image_data): void
    {
        $this->image = $image_data;

        if (empty($this->image)) {
            $this->prepareGalleryImages();
        }
    }

    public function openGallery(int $index = 0): void
    {
        if (count($this->gallery_images) === 0) {
            $this->prepareGalleryImages();
        }

        $index = max(0, min($index, count($this->gallery_images) - 1));

        $this->current_gallery_index = $index;
        $this->show_gallery_view = true;
        $this->current_url_copied = false;
    }

    public function closeGallery(): void
    {
        $this->show_gallery_view = false;
    }

    public function navigateGallery(string $direction): void
    {
        $count = count($this->gallery_images);
        if ($count === 0) {
            $this->closeGallery();

            return;
        }

        if ($direction === 'next') {
            $this->current_gallery_index = ($this->current_gallery_index + 1) % $count;
        } else {
            $this->current_gallery_index = ($this->current_gallery_index - 1 + $count) % $count;
        }

        $this->current_url_copied = false;
    }

    public function copyToClipboard(string $url): void
    {
        $this->dispatch('copy-to-clipboard', url: $url);
        $this->current_url_copied = true;
    }

    public function deleteImage(): void
    {
        if (!$this->image || !$this->gallery) {
            session()->flash('error', 'No image selected for deletion.');
            return;
        }

        $result = $this->image_gallery_http_service->deleteGalleryImage(
            $this->gallery['id'],
            $this->image['id']
        );

        if ($result) {
            session()->flash('message', 'Image deleted successfully!');
            $this->redirect(route('galleries.show', $this->gallery['id']), navigate: true);
        } else {
            session()->flash('error', 'Failed to delete image. Please try again.');
        }
    }

    public function render(): \Illuminate\View\View
    {
        return view('livewire.galleries.images.gallery-image-show');
    }

    private function prepareGalleryImages(): void
    {
        $this->gallery_images = [];

        if (isset($this->image['file_url'])) {
            $this->gallery_images[] = [
                'url' => $this->image['file_url'],
                'title' => 'Original',
            ];

            if (isset($this->image['presets']) && is_array($this->image['presets'])) {
                foreach ($this->image['presets'] as $preset_name => $preset_url) {
                    $this->gallery_images[] = [
                        'url' => $preset_url,
                        'title' => ucfirst($preset_name),
                    ];
                }
            }
        }
    }
}
