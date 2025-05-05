<?php

namespace App\Livewire\Galleries\Images;

use Livewire\Component;
use Livewire\Attributes\Validate;
use App\Services\ImageGalleryHttp\ImageGalleryHttpServiceInterface;

class GalleryImageEdit extends Component
{
    public string $gallery_id = '';
    public ?array $image = null;
    
    #[Validate('required|string|max:255')]
    public $title = '';
    
    #[Validate('string|max:255')]
    public $alt_text = '';
    
    #[Validate('string|max:10000')]
    public $description = '';
    
    private ImageGalleryHttpServiceInterface $imageGalleryHttpService;
    
    public function boot()
    {
        $this->imageGalleryHttpService = app(ImageGalleryHttpServiceInterface::class);
    }
    
    public function mount($gallery_id, $id)
    {
        $this->gallery_id = $gallery_id;
        $this->loadImage($id);
    }
    
    private function loadImage($image_id)
    {
        try {
            $this->image = $this->imageGalleryHttpService->getGalleryImage($this->gallery_id, $image_id);
            
            if (!$this->image) {
                session()->flash('error', 'Image not found.');
                return redirect()->route('galleries.show', $this->gallery_id);
            }
            
            $this->title = $this->image['title'] ?? '';
            $this->alt_text = $this->image['alt_text'] ?? '';
            $this->description = $this->image['description'] ?? '';
            
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to load image. Please try again later.');
            return redirect()->route('galleries.show', $this->gallery_id);
        }
    }
    
    public function updateImage()
    {
        $this->validate();
        
        try {
            $result = $this->imageGalleryHttpService->updateGalleryImage(
                gallery_id: $this->gallery_id,
                image_id: $this->image['id'],
                title: $this->title,
                alt_text: $this->alt_text ?? '',
                description: $this->description ?? ''
            );
            
            if ($result['success']) {
                session()->flash('message', 'Image updated successfully!');
                return redirect()->route('galleries.image.show', [
                    'gallery_id' => $this->gallery_id,
                    'id' => $this->image['id']
                ]);
            }
            
            if (isset($result['errors']) && !empty($result['errors'])) {
                foreach ($result['errors'] as $field => $messages) {
                    foreach ((array)$messages as $message) {
                        $this->addError($field, $message);
                    }
                }
            } else {
                session()->flash('error', $result['message'] ?? 'Failed to update image. Please try again.');
            }
        } catch (\Exception $e) {
            session()->flash('error', 'An unexpected error occurred. Please try again later.');
            return redirect()->route('galleries.image.show', [
                'gallery_id' => $this->gallery_id,
                'id' => $this->image['id']
            ]);
        }
    }
    
    public function render()
    {
        return view('livewire.galleries.images.gallery-image-edit');
    }
}
