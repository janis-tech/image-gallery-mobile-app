<?php

namespace App\Livewire\Galleries\Images;

use App\Services\ImageGalleryHttp\ImageGalleryHttpServiceInterface;
use Livewire\Attributes\On;
use Livewire\Component;

class GalleryImageShow extends Component
{
    public $image = null;

    public $show_gallery_view = false;

    public $current_gallery_index = 0;

    public $gallery_images = [];

    public $current_url_copied = false;

    public array $gallery = [];

    private ImageGalleryHttpServiceInterface $image_gallery_http_service;

    public function boot()
    {
        $this->image_gallery_http_service = app(ImageGalleryHttpServiceInterface::class);
    }

    public function mount($gallery_id, $id)
    {
        $this->image = $this->image_gallery_http_service->getGalleryImage($gallery_id, $id);
        $this->gallery = $this->image_gallery_http_service->getGallery($gallery_id);
    }

    #[On('show-image')]
    public function showImage($image_data)
    {
        $this->image = $image_data;

        if ($this->image) {
            $this->prepareGalleryImages();
        }
    }

    private function prepareGalleryImages()
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

    public function openGallery($index = 0)
    {
        if (count($this->gallery_images) === 0) {
            $this->prepareGalleryImages();

            if (count($this->gallery_images) === 0) {
                return;
            }
        }

        $index = max(0, min($index, count($this->gallery_images) - 1));

        $this->current_gallery_index = $index;
        $this->show_gallery_view = true;
        $this->current_url_copied = false;
    }

    public function closeGallery()
    {
        $this->show_gallery_view = false;
    }

    public function navigateGallery($direction)
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

    public function copyToClipboard($url)
    {
        $this->dispatch('copy-to-clipboard', url: $url);
        $this->current_url_copied = true;
    }

    public function deleteImage()
    {
        if (!$this->image || !isset($this->image['id'])) {
            session()->flash('error', 'No image selected for deletion.');
            return;
        }

        $result = $this->image_gallery_http_service->deleteGalleryImage(
            $this->gallery['id'],
            $this->image['id']
        );

        if ($result) {
            session()->flash('message', 'Image deleted successfully!');
            return $this->redirect(route('galleries.show', $this->gallery['id']), navigate: true);
        } else {
            session()->flash('error', 'Failed to delete image. Please try again.');
        }
    }

    public function render()
    {
        return view('livewire.galleries.images.gallery-image-show');
    }
}
