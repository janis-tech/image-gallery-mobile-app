<?php

namespace App\Livewire\Galleries;

use App\Services\ImageGalleryHttp\ImageGalleryHttpServiceInterface;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Exception;

class GalleryEdit extends Component
{
    public string $gallery_id;

    /**
     * @var array<string, mixed>
     */
    public ?array $gallery;

    #[Validate('required|string|max:255')]
    public string $name = '';

    #[Validate('string|max:255')]
    public string $description = '';

    private ImageGalleryHttpServiceInterface $imageGalleryHttpService;

    public function boot(): void
    {
        $this->imageGalleryHttpService = app(ImageGalleryHttpServiceInterface::class);
    }

    public function mount(string $id): void
    {
        $this->gallery_id = $id;
        $this->loadGallery();
    }

    public function loadGallery(): void
    {
        try {
            $gallery_dto = $this->imageGalleryHttpService->getGallery($this->gallery_id);
            $this->gallery = $gallery_dto->toArray();

            if (! $this->gallery) {
                session()->flash('error', 'Gallery not found.');

                $this->redirect(route('galleries.list'), navigate: true);
            }

            $this->name = $this->gallery['name'];
            $this->description = $this->gallery['description'];
        } catch (Exception $e) {
            session()->flash('error', 'Failed to load gallery. Please try again later.');

            $this->redirect(route('galleries.list'), navigate: true);
        }
    }

    public function updateGallery(): void
    {
        $this->validate();

        try {
            $result = $this->imageGalleryHttpService->updateGallery(
                id: $this->gallery_id,
                name: $this->name,
                description: $this->description ?? ''
            );

            if ($result['success']) {
                session()->flash('message', 'Gallery updated successfully!');

                $this->redirect(route('galleries.list'), navigate: true);
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
        } catch (Exception $e) {
            session()->flash('error', 'An unexpected error occurred. Please try again later.');

            $this->redirect(route('galleries.list'), navigate: true);
        }
    }

    public function render(): \Illuminate\View\View
    {
        return view('livewire.galleries.gallery-edit');
    }
}
