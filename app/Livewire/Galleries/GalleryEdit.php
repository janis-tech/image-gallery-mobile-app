<?php

namespace App\Livewire\Galleries;

use App\Services\ImageGalleryHttp\ImageGalleryHttpServiceInterface;
use Livewire\Attributes\Validate;
use Livewire\Component;

class GalleryEdit extends Component
{
    public string $gallery_id;

    public ?array $gallery;

    #[Validate('required|string|max:255')]
    public $name = '';

    #[Validate('string|max:255')]
    public $description = '';

    private ImageGalleryHttpServiceInterface $imageGalleryHttpService;

    public function boot()
    {
        $this->imageGalleryHttpService = app(ImageGalleryHttpServiceInterface::class);
    }

    public function mount($id)
    {
        $this->gallery_id = $id;
        $this->loadGallery();
    }

    public function loadGallery()
    {

        try {
            $this->gallery = $this->imageGalleryHttpService->getGallery($this->gallery_id);

            if (! $this->gallery) {
                session()->flash('error', 'Gallery not found.');

                return $this->redirect(route('galleries.list'), navigate: true);
            }

            $this->name = $this->gallery['name'];
            $this->description = $this->gallery['description'] ?? '';
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to load gallery. Please try again later.');

            return $this->redirect(route('galleries.list'), navigate: true);
        }
    }

    public function updateGallery()
    {

        $this->validate();

        try {

            $this->validate();

            $result = $this->imageGalleryHttpService->updateGallery(
                id: $this->gallery_id,
                name: $this->name,
                description: $this->description ?? ''
            );

            if ($result['success']) {
                session()->flash('message', 'Gallery updated successfully!');

                return $this->redirect(route('galleries.list'), navigate: true);
            }

            if (isset($result['errors']) && ! empty($result['errors'])) {
                foreach ($result['errors'] as $field => $messages) {
                    foreach ((array) $messages as $message) {
                        $this->addError($field, $message);
                    }
                }
            } else {
                session()->flash('error', $result['message'] ?? 'Failed to update gallery. Please try again.');
            }
        } catch (\Exception $e) {
            session()->flash('error', 'An unexpected error occurred. Please try again later.');

            return $this->redirect(route('galleries.list'), navigate: true);
        }
    }

    public function render()
    {
        return view('livewire.galleries.gallery-edit');
    }
}
