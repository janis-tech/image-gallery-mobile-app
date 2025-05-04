<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-6 text-gray-800 dark:text-gray-100">My Galleries</h1>
    
    <!-- Grid Layout -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse ($galleries as $gallery)
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300 border border-gray-100 dark:border-gray-700">
                <!-- Gallery Cover Image -->
                <div class="relative h-48 bg-gray-200 dark:bg-gray-700">
                    <img 
                        src="{{ $gallery['cover_image'] }}" 
                        alt="{{ $gallery['title'] }}" 
                        class="w-full h-full object-cover"
                    >
                    <!-- Image Count Badge -->
                    <div class="absolute bottom-2 right-2 bg-black bg-opacity-60 dark:bg-black dark:bg-opacity-70 text-white px-2 py-1 rounded-md flex items-center text-sm">
                        <x-flux::icon.layout-grid variant="micro" class="mr-1" />
                        {{ $gallery['images_count'] }} images
                    </div>
                </div>
                
                <!-- Gallery Info -->
                <div class="p-4">
                    <h3 class="font-semibold text-xl mb-1 text-gray-800 dark:text-gray-100">{{ $gallery['title'] }}</h3>
                    <p class="text-gray-600 dark:text-gray-400 text-sm mb-3 line-clamp-2">{{ $gallery['description'] }}</p>
                    
                    <!-- Action Buttons -->
                    <div class="flex justify-between items-center pt-2 border-t border-gray-100 dark:border-gray-700">
                        <button class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300 text-sm font-medium">View Gallery</button>
                        <div class="flex space-x-2">
                            <button class="text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                                </svg>
                            </button>
                            <button class="text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full py-12 flex flex-col items-center justify-center bg-gray-50 dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-400 dark:text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                <h3 class="mt-4 text-lg font-medium text-gray-900 dark:text-gray-100">No galleries found</h3>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Get started by creating your first gallery.</p>
                <button class="mt-4 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 dark:bg-indigo-500 dark:hover:bg-indigo-600 text-white rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800">
                    Create New Gallery
                </button>
            </div>
        @endforelse
    </div>
</div>
