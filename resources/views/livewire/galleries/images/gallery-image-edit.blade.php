<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Edit Image</h1>
        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Update your image details and metadata.</p>
    </div>

    @if($image)
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Image Preview Column -->
            <div class="bg-white dark:bg-[#161615] rounded-lg shadow-md p-6 border border-gray-200 dark:border-[#3E3E3A]">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Image Preview</h2>
                <div class="relative aspect-square bg-gray-100 dark:bg-gray-800 rounded-lg overflow-hidden mb-4">
                    @if(isset($image['presets']['medium']))
                        <img 
                            src="{{ $image['presets']['medium'] }}" 
                            alt="{{ $image['title'] ?? $image['original_filename'] }}" 
                            class="w-full h-full object-contain"
                        >
                    @elseif(isset($image['file_url']))
                        <img 
                            src="{{ $image['file_url'] }}" 
                            alt="{{ $image['title'] ?? $image['original_filename'] }}" 
                            class="w-full h-full object-contain"
                        >
                    @else
                        <div class="flex items-center justify-center h-full">
                            <span class="text-gray-500 dark:text-gray-400">No preview available</span>
                        </div>
                    @endif
                </div>
                
                <div class="text-sm text-gray-600 dark:text-gray-400 space-y-1">
                    <p>Original Filename: <span class="font-medium">{{ $image['original_filename'] ?? 'Unknown' }}</span></p>
                    @if(isset($image['width']) && isset($image['height']))
                        <p>Dimensions: <span class="font-medium">{{ $image['width'] }} × {{ $image['height'] }}</span></p>
                    @endif
                    @if(isset($image['file_size']))
                        <p>Size: <span class="font-medium">{{ number_format($image['file_size'] / 1024, 2) }} KB</span></p>
                    @endif
                </div>
            </div>

            <!-- Edit Form Column -->
            <div class="lg:col-span-2 bg-white dark:bg-[#161615] rounded-lg shadow-md p-6 border border-gray-200 dark:border-[#3E3E3A]">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Image Details</h2>
                
                <form wire:submit="updateImage">
                    <!-- Title Field -->
                    <div class="mb-4">
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

                    <!-- Alt Text Field -->
                    <div class="mb-4">
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

                    <!-- Description Field -->
                    <div class="mb-6">
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

                    <!-- Form Actions -->
                    <div class="flex items-center justify-end space-x-4">
                        <a href="{{ route('galleries.image.show', ['gallery_id' => $gallery_id, 'id' => $image['id']]) }}" wire:navigate
                            class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-400">
                            Cancel
                        </a>
                        <button type="submit"
                            class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 dark:bg-indigo-500 dark:hover:bg-indigo-600 text-white font-medium rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                            </svg>
                            Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @else
        <div class="bg-white dark:bg-[#161615] rounded-lg shadow-md p-8 border border-gray-200 dark:border-[#3E3E3A] flex flex-col items-center justify-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-400 dark:text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg>
            <h3 class="mt-4 text-lg font-medium text-gray-900 dark:text-gray-100">Image not found</h3>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">The requested image could not be loaded.</p>
            <a href="{{ route('galleries.list') }}" wire:navigate class="mt-4 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 dark:bg-indigo-500 dark:hover:bg-indigo-600 text-white rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800">
                Return to Galleries
            </a>
        </div>
    @endif
</div>
