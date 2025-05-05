<div class="container mx-auto px-4 py-8">
    <!-- Breadcrumbs -->
    <div class="mb-6">
        <x-ui.breadcrumbs :items="[
            ['label' => 'Home', 'url' => route('dashboard')],
            ['label' => 'Galleries', 'url' => route('galleries.list')],
            ['label' => $gallery['name'] ?? 'Gallery', 'url' => route('galleries.show', $gallery['id'])],
            ['label' => 'Upload Image'],
        ]" />
    </div>

    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Upload Image</h1>
        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Add new images to your gallery.</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Image Upload Column -->
        <div class="bg-white dark:bg-[#161615] rounded-lg shadow-md p-6 border border-gray-200 dark:border-[#3E3E3A]">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Image Selection</h2>
            
            <!-- Image Upload Area -->
            <div class="mb-5">
                <div class="relative border-2 border-dashed border-gray-300 dark:border-gray-700 rounded-lg p-6 flex flex-col justify-center items-center bg-gray-50 dark:bg-gray-900 transition-all duration-200 aspect-square">
                    @if(!empty($image_data_url))
                        <div class="relative w-full h-full">
                            <img src="{{ $image_data_url }}" alt="Preview" class="w-full h-full object-contain rounded">
                            <button 
                                type="button"
                                wire:click="removeImage"
                                class="absolute top-2 right-2 bg-red-500 text-white rounded-full p-1 hover:bg-red-600 focus:outline-none"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    @else
                        <div class="space-y-2 flex flex-col items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Select an image or use the buttons below</p>
                        </div>
                    @endif
                    
                    <input 
                        id="image-upload" 
                        type="file" 
                        wire:model="image" 
                        class="hidden"
                        accept="image/*"
                    >
                </div>
                @error('image')
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>
            
            <!-- Image Selection Buttons -->
            <div class="grid grid-cols-2 gap-4 mb-5">
                <button 
                    type="button"
                    onclick="document.getElementById('image-upload').click()"
                    class="w-full inline-flex justify-center items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-md focus:outline-none"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd" />
                    </svg>
                    Select File
                </button>
                <button 
                    type="button"
                    wire:click="toggleCamera"
                    class="w-full inline-flex justify-center items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-md focus:outline-none"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M4 5a2 2 0 00-2 2v8a2 2 0 002 2h12a2 2 0 002-2V7a2 2 0 00-2-2h-1.586a1 1 0 01-.707-.293l-1.121-1.121A2 2 0 0011.172 3H8.828a2 2 0 00-1.414.586L6.293 4.707A1 1 0 015.586 5H4zm6 9a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd" />
                    </svg>
                    Take Photo
                </button>
            </div>
        </div>

        <!-- Image Details Column -->
        <div class="lg:col-span-2 bg-white dark:bg-[#161615] rounded-lg shadow-md p-6 border border-gray-200 dark:border-[#3E3E3A]">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Image Details</h2>
            
            <form wire:submit="uploadImage">
                <div class="space-y-4">
                    <!-- Title -->
                    <div>
                        <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Title</label>
                        <input
                            type="text"
                            id="title"
                            wire:model="title"
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-400"
                            placeholder="Enter image title"
                        >
                        @error('title')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Alt Text -->
                    <div>
                        <label for="alt_text" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Alt Text</label>
                        <input
                            type="text"
                            id="alt_text"
                            wire:model="alt_text"
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-400"
                            placeholder="Describe the image for accessibility"
                        >
                        @error('alt_text')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Description -->
                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Description</label>
                        <textarea
                            id="description"
                            wire:model="description"
                            rows="4"
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-400"
                            placeholder="Provide additional details about this image"
                        ></textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="flex items-center justify-end space-x-4 mt-6">
                    <a href="{{ route('galleries.show', $gallery_id) }}" wire:navigate
                        class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-400">
                        Cancel
                    </a>
                    <button
                        type="submit"
                        class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 dark:bg-indigo-500 dark:hover:bg-indigo-600 text-white font-medium rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800 disabled:opacity-50 disabled:cursor-not-allowed"
                        wire:loading.attr="disabled"
                        wire:loading.class="opacity-75 cursor-wait"
                        @if(empty($image_data_url)) disabled @endif
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor" wire:loading.remove wire:target="uploadImage">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                        </svg>
                        <svg class="animate-spin -ml-1 mr-2 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" wire:loading wire:target="uploadImage">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <span wire:loading.remove wire:target="uploadImage">Upload Image</span>
                        <span wire:loading wire:target="uploadImage">Uploading...</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
    
    @if(session('error'))
        <div class="fixed bottom-4 right-4 bg-red-500 text-white px-4 py-2 rounded shadow-lg z-50" 
            x-data="{ show: true }" 
            x-init="setTimeout(() => { show = false }, 5000)" 
            x-show="show"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 transform translate-y-2"
            x-transition:enter-end="opacity-100 transform translate-y-0"
            x-transition:leave="transition ease-in duration-300"
            x-transition:leave-start="opacity-100 transform translate-y-0"
            x-transition:leave-end="opacity-0 transform translate-y-2">
            {{ session('error') }}
        </div>
    @endif

    @if(session('message'))
        <div class="fixed bottom-4 right-4 bg-green-500 text-white px-4 py-2 rounded shadow-lg z-50" 
            x-data="{ show: true }" 
            x-init="setTimeout(() => { show = false }, 5000)" 
            x-show="show"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 transform translate-y-2"
            x-transition:enter-end="opacity-100 transform translate-y-0"
            x-transition:leave="transition ease-in duration-300"
            x-transition:leave-start="opacity-100 transform translate-y-0"
            x-transition:leave-end="opacity-0 transform translate-y-2">
            {{ session('message') }}
        </div>
    @endif
</div>