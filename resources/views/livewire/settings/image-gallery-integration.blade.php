<section class="w-full" x-data="{ 
    isRevealed: false, 
    copied: false,
    
    toggleReveal() { 
        this.isRevealed = !this.isRevealed; 
    },
    
    copyToClipboard() {
        const entityId = '{{ $image_gallery_entity_id }}';
        if (entityId) {
            navigator.clipboard.writeText(entityId).then(() => {
                this.copied = true;
                setTimeout(() => this.copied = false, 2000);
            });
        }
    },
    
    getMaskedValue() {
        const entityId = '{{ $image_gallery_entity_id }}';
        if (!entityId) return '';
        return '•'.repeat(Math.min(16, entityId.length)) + 
               (entityId.length > 16 ? '...' + entityId.substring(entityId.length - 4) : '');
    }
}">
    @include('partials.settings-heading')

    <x-settings.layout :heading="__('Image gallery integration')" :subheading="__('See or update the image gallery integration settings.')">
        <div class="mt-6 space-y-6">
            <!-- Entity ID Display/Edit Section -->
            <div class="space-y-4">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">{{ __('Entity ID') }}</h3>
                    
                    <div class="flex items-center space-x-2">
                        <button 
                            type="button"
                            x-show="!@js($is_editing)"
                            @click="toggleReveal"
                            class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 text-sm font-medium focus:outline-none"
                        >
                            <span x-text="isRevealed ? 'Hide' : 'Reveal'"></span>
                        </button>
                        
                        <button
                            type="button"
                            x-show="!@js($is_editing) && @js((bool)$image_gallery_entity_id)"
                            @click="copyToClipboard"
                            class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 text-sm font-medium focus:outline-none flex items-center"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                            </svg>
                            <span x-text="copied ? 'Copied!' : 'Copy'"></span>
                        </button>
                        
                        <button
                            type="button"
                            wire:click="$set('is_editing', {{ !$is_editing }})"
                            class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 text-sm font-medium focus:outline-none flex items-center"
                        >
                            @if(!$is_editing)
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                </svg>
                            @endif
                            <span>{{ $is_editing ? __('Cancel') : __('Edit') }}</span>
                        </button>
                    </div>
                </div>
                
                <!-- Display Mode -->
                @if(!$is_editing)
                    <div class="bg-gray-100 dark:bg-gray-800 rounded-lg p-4">
                        <p class="font-mono break-all" x-html="isRevealed ? '{{ $image_gallery_entity_id }}' : getMaskedValue()"></p>
                    </div>
                @else
                    <!-- Edit Mode -->
                    <form wire:submit.prevent="updateImageGalleryIntegration" class="space-y-4">
                        <div>
                            <flux:input 
                                wire:model="image_gallery_entity_id" 
                                type="text" 
                                autocomplete="off"
                                class="font-mono"
                                placeholder="Enter your image gallery entity ID"
                            />
                            
                            @if($validation_error)
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $validation_error }}</p>
                            @endif
                        </div>
                        
                        <div class="rounded-md bg-yellow-50 dark:bg-yellow-900/30 p-4">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-yellow-400 dark:text-yellow-500" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                        <path fill-rule="evenodd" d="M8.485 2.495c.673-1.167 2.357-1.167 3.03 0l6.28 10.875c.673 1.167-.17 2.625-1.516 2.625H3.72c-1.347 0-2.189-1.458-1.515-2.625L8.485 2.495zM10 5a.75.75 0 01.75.75v4.5a.75.75 0 01-1.5 0v-4.5A.75.75 0 0110 5zm0 10a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-sm font-medium text-yellow-800 dark:text-yellow-200">{{ __('Warning') }}</h3>
                                    <div class="mt-2 text-sm text-yellow-700 dark:text-yellow-300">
                                        <p>
                                            {{ __('Changing this entity ID will link your account to a different set of galleries and images. If you lose this ID, you may lose access to your existing galleries.') }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center gap-4">
                            <div class="flex items-center space-x-3">
                                <flux:button variant="primary" type="submit">{{ __('Save') }}</flux:button>
                                <flux:button variant="filled" type="button" wire:click="cancelEdit">{{ __('Cancel') }}</flux:button>
                            </div>
                        </div>
                    </form>
                @endif
                
                <!-- Success Message -->
                <div class="mt-3">
                    <x-action-message class="me-3" on="image-gallery-integration-updated">
                        {{ __('Saved.') }}
                    </x-action-message>
                </div>
            </div>
        </div>
    </x-settings.layout>
</section>
