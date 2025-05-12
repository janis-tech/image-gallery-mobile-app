<?php

namespace App\Livewire\Galleries\Images;

use App\Services\ImageGalleryHttp\ImageGalleryHttpServiceInterface;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Exception;

class GalleryImageEdit extends Component
{
    public string $gallery_id = '';

    /**
     * @var array<string, mixed>
     */
    public array $gallery;

    /**
     * @var array<string, mixed>
     */
    public array $image = [];

    #[Validate('string|max:255')]
    public string $title = '';

    #[Validate('string|max:255')]
    public string $alt_text = '';

    #[Validate('string|max:10000')]
    public string $description = '';

    private ImageGalleryHttpServiceInterface $imageGalleryHttpService;

    public function boot(): void
    {
        $this->imageGalleryHttpService = app(ImageGalleryHttpServiceInterface::class);
    }

    public function mount(string $gallery_id, string $id): void
    {
        $this->gallery_id = $gallery_id;
        $gallery_dto = $this->imageGalleryHttpService->getGallery($this->gallery_id);
        $this->gallery = $gallery_dto->toArray();
        $this->loadImage($id);
    }

    public function updateImage(): void
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

                $this->redirect(route('galleries.image.show', [
                    'gallery_id' => $this->gallery_id,
                    'id' => $this->image['id'],
                ]), navigate: true);
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

            $this->redirect(route('galleries.image.show', [
                'gallery_id' => $this->gallery_id,
                'id' => $this->image['id'],
            ]), navigate: true);
        }
    }

    public function render(): \Illuminate\View\View
    {
        return view('livewire.galleries.images.gallery-image-edit');
    }

    private function loadImage(string $image_id): void
    {
        try {
            $image_dto = $this->imageGalleryHttpService->getGalleryImage($this->gallery_id, $image_id);
            $this->image = $image_dto->toArray();

            if (! $this->image) {
                session()->flash('error', 'Image not found.');

                $this->redirect(route('galleries.show', $this->gallery_id), navigate: true);
            }

            $this->title = $this->image['title'] ;
            $this->alt_text = $this->image['alt_text'];
            $this->description = $this->image['description'];
        } catch (Exception $e) {
            session()->flash('error', 'Failed to load image. Please try again later.');

            $this->redirect(route('galleries.show', $this->gallery_id), navigate: true);
        }
    }
}
