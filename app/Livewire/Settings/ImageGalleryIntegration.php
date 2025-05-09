<?php

namespace App\Livewire\Settings;

use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Validate;
use Livewire\Component;

class ImageGalleryIntegration extends Component
{
    #[Validate('nullable|string|size:120')]
    public string $image_gallery_entity_id = '';

    public bool $is_editing = false;

    public ?string $validation_error = null;

    public function mount(): void
    {
        $this->image_gallery_entity_id = Auth::user()->image_gallery_entity_id ?? '';
    }

    public function updateImageGalleryIntegration(): void
    {
        try {
            $validated = $this->validate();

            Auth::user()->update([
                'image_gallery_entity_id' => $validated['image_gallery_entity_id'],
            ]);

            $this->is_editing = false;
            $this->validation_error = null;
            $this->dispatch('image-gallery-integration-updated');
        } catch (\Illuminate\Validation\ValidationException $e) {
            $this->validation_error = $e->errors()['image_gallery_entity_id'][0] ?? 'Validation failed';
        }
    }

    public function cancelEdit(): void
    {
        $this->is_editing = false;
        $this->validation_error = null;
        $this->image_gallery_entity_id = Auth::user()->image_gallery_entity_id ?? '';
        $this->resetValidation();
    }

    public function render(): \Illuminate\View\View
    {
        return view('livewire.settings.image-gallery-integration');
    }
}
