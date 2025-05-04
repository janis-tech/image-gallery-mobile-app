<div class="container mx-auto px-4 py-8">
    <!-- Header with Search -->
    <div class="flex flex-col mb-6">
        <!-- Search Field with Live Updates -->
        <div class="w-full">
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="w-5 h-5 text-gray-400 dark:text-gray-500" xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                            clip-rule="evenodd" />
                    </svg>
                </div>
                <input wire:model.live.debounce.300ms="search" type="text" placeholder="Search galleries..."
                    class="w-full pl-10 pr-3 py-2 border border-gray-300 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-400">
            </div>
        </div>
    </div>

    <!-- Grid Layout -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse ($galleries as $gallery)
            <div
                class="relative bg-white dark:bg-[#161615] rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-all duration-300 border border-gray-100 dark:border-[#3E3E3A] group">
                <a href="{{ route('galleries.show', $gallery['id']) ?? '#' }}" wire:navigate
                    class="absolute inset-0 z-10">
                    <span class="sr-only">View {{ $gallery['name'] }}</span>
                </a>
                <div class="relative h-48 bg-gray-200 dark:bg-gray-700">
                    @if (isset($gallery['first_image'], $gallery['first_image']['small']))
                        <img src="{{ $gallery['first_image']['small'] }}" alt="{{ $gallery['name'] }}"
                            class="w-full h-full object-cover">
                    @endif
                    <!-- Image Count Badge -->
                    <div
                        class="absolute bottom-2 right-2 bg-gray-700 bg-opacity-70 dark:bg-black dark:bg-opacity-70 text-white px-2 py-1 rounded-md flex items-center text-sm">
                        <x-flux::icon.layout-grid variant="micro" class="mr-1" />
                        {{ $gallery['images_count'] }} images
                    </div>

                    <div
                        class="absolute top-2 right-2 flex space-x-1 opacity-0 group-hover:opacity-100 transition-opacity duration-200 z-20">
                        <a href="{{ route('galleries.edit', $gallery['id']) }}" type="button"
                            class="bg-gray-700 bg-opacity-70 dark:bg-black dark:bg-opacity-70 text-white p-2 rounded-md hover:bg-opacity-80 dark:hover:bg-opacity-80 hover:scale-105 transition-all duration-150 cursor-pointer"
                            wire:navigate>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20"
                                fill="currentColor">
                                <path
                                    d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                            </svg>
                        </a>
                        <button type="button"
                            class="bg-gray-700 bg-opacity-70 dark:bg-black dark:bg-opacity-70 text-white p-2 rounded-md hover:bg-opacity-80 dark:hover:bg-opacity-80 hover:scale-105 transition-all duration-150 cursor-pointer"
                            wire:confirm="Are you sure you want to delete this gallery?"
                            wire:click="deleteGallery('{{ $gallery['id'] }}')">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20"
                                fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z"
                                    clip-rule="evenodd" />
                            </svg>
                        </button>
                    </div>
                </div>

                <div class="p-4">
                    <h3 class="font-semibold text-xl mb-1 text-gray-800 dark:text-gray-100">{{ $gallery['name'] }}</h3>
                    <p class="text-gray-600 dark:text-gray-400 text-sm line-clamp-2">{{ $gallery['description'] }}</p>
                </div>
            </div>
        @empty
            <div
                class="col-span-full py-12 flex flex-col items-center justify-center bg-white dark:bg-[#161615] rounded-lg border border-gray-200 dark:border-[#3E3E3A]">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-400 dark:text-gray-500"
                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                <h3 class="mt-4 text-lg font-medium text-gray-900 dark:text-gray-100">No galleries found</h3>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Get started by creating your first gallery.</p>
                <a href="{{ route('galleries.create') }}"
                    class="mt-4 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 dark:bg-indigo-500 dark:hover:bg-indigo-600 text-white rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800">
                    Create New Gallery
                </a>
            </div>
        @endforelse
    </div>
    <a href="{{ route('galleries.create') }}"
        class="fixed bottom-8 right-8 flex items-center justify-center w-16 h-16 rounded-full bg-indigo-600 hover:bg-indigo-700 dark:bg-indigo-500 dark:hover:bg-indigo-600 text-white shadow-lg hover:shadow-xl focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800 transition-all duration-300 z-[9999]"
        aria-label="Create Gallery" style="position: fixed; bottom: 2rem; right: 2rem;">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd"
                d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z"
                clip-rule="evenodd" />
        </svg>
    </a>
</div>
