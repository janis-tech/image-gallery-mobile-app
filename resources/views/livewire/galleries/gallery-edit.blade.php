<div class="container mx-auto px-4 py-8">
    <!-- Breadcrumbs -->
    <div class="mb-6">
        <x-ui.breadcrumbs :items="[
            ['label' => 'Home', 'url' => route('dashboard')],
            ['label' => 'Galleries', 'url' => route('galleries.list')],
            ['label' => $gallery['name'] ?? 'Gallery', 'url' => route('galleries.show', $gallery['id'])],
            ['label' => 'Edit'],
        ]" />
    </div>
    
    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Editing Your Photo Gallery "{{$gallery['name']}}"</h1>
        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Customize your gallery details.</p>
    </div>

    <!-- Gallery edit form -->
    <div class="bg-white dark:bg-[#161615] rounded-lg shadow-md p-6 border border-gray-200 dark:border-[#3E3E3A]">
        <form wire:submit="updateGallery">
            @include('partials.gallery-form', ['submitButtonText' => 'Update Gallery'])
        </form>
    </div>
</div>
