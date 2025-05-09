<?php

namespace App\Livewire\Galleries;

use App\Services\ImageGalleryHttp\ImageGalleryHttpServiceInterface;
use Livewire\Attributes\Validate;
use Livewire\Component;

class GalleryCreate extends Component
{
    #[Validate('required|string|max:255')]
    public $name = '';

    #[Validate('string|max:10000')]
    public $description = '';

    private ImageGalleryHttpServiceInterface $imageGalleryHttpService;

    public function boot()
    {
        $this->imageGalleryHttpService = app(ImageGalleryHttpServiceInterface::class);
    }

    public function createGallery()
    {
        $this->validate();

        try {

            $result = $this->imageGalleryHttpService->createGallery(
                name: $this->name,
                description: $this->description ?? ''
            );

            if ($result['success']) {
                session()->flash('message', 'Gallery created successfully!');

                return $this->redirect(route('galleries.list'), navigate: true);
            }

            if (! empty($result['errors'])) {
                foreach ($result['errors'] as $field => $messages) {
                    foreach ((array) $messages as $message) {
                        $this->addError($field, $message);
                    }
                }
            } else {
                session()->flash('error', $result['message']);
            }
        } catch (\Exception $e) {
            session()->flash('error', 'An unexpected error occurred. Please try again later.');

            return $this->redirect(route('galleries.list'), navigate: true);
        }
    }

    public function render()
    {
        return view('livewire.galleries.gallery-create');
    }
}
