<?php

namespace App\Livewire\Galleries\Images;

use App\Services\ImageGalleryHttp\ImageGalleryHttpServiceInterface;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\WithFileUploads;
use Illuminate\Support\Facades\Log;

class GalleryImageUpload extends Component
{
    use WithFileUploads;

    public string $gallery_id = '';
    public $image = null;
    public ?array $gallery = [];
    public string $title = '';
    public string $description = '';
    public string $alt_text = '';
    public bool $is_uploading = false;

    public bool $show_camera = false;

    private ImageGalleryHttpServiceInterface $imageGalleryHttpService;

    public function boot()
    {
        $this->imageGalleryHttpService = app(ImageGalleryHttpServiceInterface::class);
    }

    public function mount($gallery_id)
    {
        $this->gallery_id = $gallery_id;
        try {
            $this->gallery = $this->imageGalleryHttpService->getGallery($this->gallery_id);
            if (!$this->gallery) {
                session()->flash('error', 'Gallery not found.');
                return $this->redirect(route('galleries.list'), navigate: true);
            }
        } catch (\Exception $e) {
            Log::error('Error loading gallery', [
                'gallery_id' => $this->gallery_id,
                'error' => $e->getMessage(),
            ]);
            session()->flash('error', 'Failed to load gallery. Please try again later.');
            return $this->redirect(route('galleries.list'), navigate: true);
        }
    }

    public function toggleCamera()
    {
        $this->show_camera = !$this->show_camera;
    }

    public function removeImage()
    {
        $this->reset(['image']);
        $this->resetValidation('image');
    }

    public function uploadImage()
    {
        $this->is_uploading = true;

        try {
            $temp_path = $this->image->getRealPath();
            $file_name = $this->image->getClientOriginalName();
            
            $result = $this->imageGalleryHttpService->uploadGalleryImage(
                gallery_id: $this->gallery_id,
                file_path: $temp_path,
                title: !empty($this->title) ? $this->title : null,
                file_name: $file_name,
                description: !empty($this->description) ? $this->description : null,
                alt_text: !empty($this->alt_text) ? $this->alt_text : null
            );

            if ($result['success']) {
                session()->flash('message', 'Image uploaded successfully!');
                return $this->redirect(route('galleries.show', $this->gallery_id), navigate: true);
            } else {
                if (isset($result['errors']) && !empty($result['errors'])) {
                    foreach ($result['errors'] as $field => $messages) {
                        foreach ((array) $messages as $message) {
                            $this->addError($field, $message);
                        }
                    }
                } else {
                    session()->flash('error', $result['message'] ?? 'Failed to upload image. Please try again.');
                }
            }
        } catch (\Exception $e) {
            Log::error('Error uploading image', [
                'gallery_id' => $this->gallery_id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            session()->flash('error', 'An unexpected error occurred. Please try again later.');
        } finally {
            $this->is_uploading = false;
        }
    }

    public function render()
    {
        return view('livewire.galleries.images.gallery-image-upload');
    }
}