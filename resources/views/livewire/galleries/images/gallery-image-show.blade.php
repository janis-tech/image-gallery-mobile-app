<div class="container mx-auto px-4 py-8">
    @if($image)
        <!-- Main content container -->
        <div class="max-w-full">
            <!-- Breadcrumbs -->
            <div class="mb-6">
                <x-ui.breadcrumbs :items="[
                    ['label' => 'Home', 'url' => route('dashboard')],
                    ['label' => 'Galleries', 'url' => route('galleries.list')],
                    ['label' => $gallery['name'] ?? 'Gallery', 'url' => route('galleries.show', $gallery['id'])],
                    ['label' => $image['title'] ?? $image['original_filename']],
                ]" />
            </div>
            
            <!-- Top action buttons -->
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold dark:text-white">
                    {{ $image['title'] ?? $image['original_filename'] }}
                </h1>
                <div class="flex space-x-2">
                    <a href="{{ route('galleries.image.edit', [$gallery['id'], $image['id']]) }}" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-md flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                        </svg>
                        Edit
                    </a>
                    <button class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-md flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                        Delete
                    </button>
                </div>
            </div>
            
            <div class="flex flex-col md:flex-row gap-6">
                <div class="md:w-[30%] space-y-6">
                    <div class="bg-gray-100 dark:bg-gray-800 rounded-lg p-4">
                        <div class="border border-gray-300 dark:border-gray-600 rounded-lg overflow-hidden shadow-sm hover:shadow-md transition-shadow cursor-pointer"
                             wire:click="openGallery(0)">
                            <img src="{{ $image['presets']['large'] }}" 
                                 alt="{{ $image['alt_text'] ?? $image['original_filename'] }}" 
                                 class="w-full h-auto object-contain">
                        </div>
                        <div class="flex justify-between items-center mt-2">
                            <span class="text-sm text-gray-600 dark:text-gray-300">
                                {{ $image['width'] }} × {{ $image['height'] }} • {{ number_format($image['file_size'] / 1024 / 1024, 2) }} MB
                            </span>
                            <button 
                                class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 focus:outline-none"
                                wire:click="copyToClipboard('{{ $image['file_url'] }}')"
                            >
                                <div class="flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                    </svg>
                                    <span class="text-sm">Copy URL</span>
                                </div>
                            </button>
                        </div>
                    </div>
                    
                    <div class="bg-gray-100 dark:bg-gray-800 rounded-lg p-4">
                        <h2 class="text-lg font-semibold dark:text-white mb-3">Image Presets</h2>
                        <div class="grid grid-cols-2 gap-3">
                            @if(isset($image['presets']))
                                @foreach($image['presets'] as $presetName => $presetUrl)
                                    <div class="bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg overflow-hidden">
                                        <div class="cursor-pointer" 
                                             wire:click="openGallery({{ $loop->index + 1 }})">
                                            <img src="{{ $presetUrl }}" 
                                                 alt="{{ $presetName }} preset" 
                                                 class="w-full h-28 object-cover">
                                        </div>
                                        <div class="p-2 flex justify-between items-center bg-gray-50 dark:bg-gray-800 border-t border-gray-200 dark:border-gray-600">
                                            <span class="text-xs font-medium text-gray-700 dark:text-gray-300 capitalize">{{ $presetName }}</span>
                                            <button 
                                                class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 focus:outline-none"
                                                wire:click.stop="copyToClipboard('{{ $presetUrl }}')"
                                            >
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    </div>
                </div>
                
                <div class="md:w-[70%] space-y-6">
                    <div class="bg-gray-100 dark:bg-gray-800 rounded-lg p-4">
                        <div class="mb-4">
                            <h2 class="text-lg font-semibold dark:text-white mb-2">Description</h2>
                            <p class="text-gray-700 dark:text-gray-300">
                                {{ $image['description'] ?? 'No description provided.' }}
                            </p>
                        </div>
                        
                        <div>
                            <h2 class="text-lg font-semibold dark:text-white mb-2">AI Generated Caption</h2>
                            <p class="text-gray-700 dark:text-gray-300 italic">
                                {{ $image['generated_caption'] ?? 'No AI caption available.' }}
                            </p>
                        </div>
                    </div>
                    
                    <div class="bg-gray-100 dark:bg-gray-800 rounded-lg p-4">
                        <h2 class="text-lg font-semibold dark:text-white mb-3">Image Information</h2>
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                            <div class="space-y-2">
                                <div class="flex flex-col">
                                    <span class="text-sm font-medium text-gray-600 dark:text-gray-400">Alt Text</span>
                                    <span class="dark:text-gray-300">{{ $image['alt_text'] ?? 'None' }}</span>
                                </div>
                                <div class="flex flex-col">
                                    <span class="text-sm font-medium text-gray-600 dark:text-gray-400">Original Filename</span>
                                    <span class="dark:text-gray-300">{{ $image['original_filename'] }}</span>
                                </div>
                                <div class="flex flex-col">
                                    <span class="text-sm font-medium text-gray-600 dark:text-gray-400">File Type</span>
                                    <span class="dark:text-gray-300">{{ $image['mime_type'] }}</span>
                                </div>
                                <div class="flex flex-col">
                                    <span class="text-sm font-medium text-gray-600 dark:text-gray-400">Dimensions</span>
                                    <span class="dark:text-gray-300">{{ $image['width'] }} × {{ $image['height'] }} pixels</span>
                                </div>
                            </div>
                            <div class="space-y-2">
                                <div class="flex flex-col">
                                    <span class="text-sm font-medium text-gray-600 dark:text-gray-400">File Size</span>
                                    <span class="dark:text-gray-300">{{ number_format($image['file_size'] / 1024, 2) }} KB</span>
                                </div>
                                <div class="flex flex-col">
                                    <span class="text-sm font-medium text-gray-600 dark:text-gray-400">Uploaded At</span>
                                    <span class="dark:text-gray-300">{{ \Carbon\Carbon::parse($image['created_at'])->format('M d, Y H:i') }}</span>
                                </div>
                                <div class="flex flex-col">
                                    <span class="text-sm font-medium text-gray-600 dark:text-gray-400">ID</span>
                                    <span class="truncate dark:text-gray-300">{{ $image['id'] }}</span>
                                </div>
                                <div class="flex flex-col">
                                    <span class="text-sm font-medium text-gray-600 dark:text-gray-400">Gallery ID</span>
                                    <span class="truncate dark:text-gray-300">{{ $image['gallery_id'] }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if($show_gallery_view && count($gallery_images) > 0)
            <div class="fixed inset-0 bg-black bg-opacity-90 z-50 flex flex-col justify-center items-center">
                <div class="w-full flex justify-between items-center px-4 py-3 bg-black bg-opacity-70 fixed top-0 z-10">
                    <div class="text-white text-lg">
                        {{ $gallery_images[$current_gallery_index]['title'] ?? '' }}
                    </div>
                    <div class="flex items-center space-x-4">
                        <button 
                            class="text-white hover:text-blue-300 focus:outline-none flex items-center"
                            wire:click="copyToClipboard('{{ $gallery_images[$current_gallery_index]['url'] ?? '' }}')"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                            </svg>
                            <span>{{ $current_url_copied ? 'Copied!' : 'Copy URL' }}</span>
                        </button>
                        <button 
                            class="text-white hover:text-red-400 focus:outline-none"
                            wire:click="closeGallery"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>
                
                <div class="flex-grow w-full flex items-center justify-center relative p-4">
                    <img 
                        src="{{ $gallery_images[$current_gallery_index]['url'] ?? '' }}" 
                        alt="{{ $gallery_images[$current_gallery_index]['title'] ?? 'Image preview' }}"
                        class="max-w-full max-h-[calc(100vh-120px)] object-contain"
                    >
                    
                    @if(count($gallery_images) > 1)
                        <button 
                            class="absolute left-4 top-1/2 transform -translate-y-1/2 bg-black bg-opacity-50 rounded-full p-2 text-white hover:bg-opacity-70 focus:outline-none"
                            wire:click="navigateGallery('prev')"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                            </svg>
                        </button>
                        <button 
                            class="absolute right-4 top-1/2 transform -translate-y-1/2 bg-black bg-opacity-50 rounded-full p-2 text-white hover:bg-opacity-70 focus:outline-none"
                            wire:click="navigateGallery('next')"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </button>
                    @endif
                </div>
                
                @if(count($gallery_images) > 1)
                    <div class="w-full bg-black bg-opacity-70 fixed bottom-0 py-3 px-4 overflow-x-auto">
                        <div class="flex space-x-2">
                            @foreach($gallery_images as $index => $galleryImage)
                                <div 
                                    wire:click="openGallery({{ $index }})" 
                                    class="w-16 h-16 flex-shrink-0 cursor-pointer {{ $current_gallery_index === $index ? 'border-2 border-blue-500' : '' }}"
                                >
                                    <img 
                                        src="{{ $galleryImage['url'] }}" 
                                        alt="{{ $galleryImage['title'] }}" 
                                        class="w-full h-full object-cover"
                                    >
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        @endif
    @else
        <div class="flex flex-col items-center justify-center h-64">
            <p class="text-gray-500 dark:text-gray-400">No image selected</p>
        </div>
    @endif
</div>

<script>
    document.addEventListener('livewire:initialized', function () {
        Livewire.on('copy-to-clipboard', async function (data) {
            try {
                await navigator.clipboard.writeText(data.url);
            } catch (err) {
                console.error('Failed to copy: ', err);
            }
        });
    });
</script>
