<?php

namespace App\Livewire\Galleries\Images;

use App\Services\ImageGalleryHttp\DTOs\GalleryDTO;
use App\Services\ImageGalleryHttp\ImageGalleryHttpServiceInterface;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\WithFileUploads;
use Native\Mobile\Events\Camera\PhotoTaken;
use Native\Mobile\Facades\System;

class GalleryImageUpload extends Component
{
    use WithFileUploads;

    public string $gallery_id = '';

    public $image = null;

    public string $image_data_url = '';

    public string $temp_file_path = '';

    public array $gallery;

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
            $gallery_dto = $this->imageGalleryHttpService->getGallery($this->gallery_id);
            $this->gallery = $gallery_dto->toArray();
            
            if (! $this->gallery) {
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
        $this->show_camera = ! $this->show_camera;
        $status = System::camera();

        if ($status) {
            $this->image = $status;
            $this->show_camera = false;
        } else {
            session()->flash('error', 'Camera access denied or not available.');
        }
    }

    #[On('native:'.PhotoTaken::class)]
    public function handleCamera($path)
    {
        try {
            $data = base64_encode(file_get_contents($path));
            $mime = mime_content_type($path);

            $this->image_data_url = "data:{$mime};base64,{$data}";
            $this->temp_file_path = $path;
            $this->image = null;

            $this->show_camera = false;
        } catch (\Exception $e) {
            Log::error('Error handling camera photo', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            session()->flash('error', 'Failed to process camera photo.');
        }
    }

    public function updatedImage()
    {
        if ($this->image) {
            try {
                $path = $this->image->getRealPath();
                $data = base64_encode(file_get_contents($path));
                $mime = mime_content_type($path);

                $this->image_data_url = "data:{$mime};base64,{$data}";
                $this->temp_file_path = $path;
            } catch (\Exception $e) {
                Log::error('Error processing uploaded file', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                ]);
                session()->flash('error', 'Failed to process uploaded image.');
            }
        }
    }

    public function removeImage()
    {
        $this->reset(['image', 'image_data_url', 'temp_file_path']);
        $this->resetValidation('image');
    }

    public function uploadImage()
    {
        $this->is_uploading = true;

        try {
            if (empty($this->temp_file_path)) {
                $this->is_uploading = false;
                session()->flash('error', 'No image selected.');

                return;
            }

            $file_name = basename($this->temp_file_path);

            $result = $this->imageGalleryHttpService->uploadGalleryImage(
                gallery_id: $this->gallery_id,
                file_path: $this->temp_file_path,
                title: ! empty($this->title) ? $this->title : null,
                file_name: $file_name,
                description: ! empty($this->description) ? $this->description : null,
                alt_text: ! empty($this->alt_text) ? $this->alt_text : null
            );

            if ($result['success']) {
                session()->flash('message', 'Image uploaded successfully!');

                return $this->redirect(route('galleries.show', $this->gallery_id), navigate: true);
            } else {
                if (isset($result['errors']) && ! empty($result['errors'])) {
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
