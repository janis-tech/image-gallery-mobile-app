@props(['items' => []])

<nav aria-label="Breadcrumb" {{ $attributes->class(['py-2']) }}>
    <ol class="flex flex-wrap items-center space-x-1 text-sm text-zinc-500">
        @foreach($items as $index => $item)
            <li class="flex items-center">
                @if($index > 0)
                    <svg class="h-4 w-4 mx-1 text-zinc-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                    </svg>
                @endif
                
                @if(isset($item['url']) && $index !== count($items) - 1)
                    <a href="{{ $item['url'] }}" wire:navigate class="hover:text-zinc-700 dark:hover:text-zinc-300 transition-colors font-medium">
                        {{ $item['label'] }}
                    </a>
                @else
                    <span class="{{ $index === count($items) - 1 ? 'text-zinc-800 dark:text-zinc-200 font-medium' : '' }}">
                        {{ $item['label'] }}
                    </span>
                @endif
            </li>
        @endforeach
    </ol>
</nav>