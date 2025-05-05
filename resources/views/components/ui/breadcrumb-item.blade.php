@props([
    'active' => false,
    'first' => false,
    'url' => null
])

<li class="flex items-center">
    @if(!$first)
        <svg class="h-4 w-4 mx-1 text-zinc-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
        </svg>
    @endif
    
    @if($url && !$active)
        <a href="{{ $url }}" class="hover:text-zinc-700 dark:hover:text-zinc-300 transition-colors font-medium">
            {{ $slot }}
        </a>
    @else
        <span class="{{ $active ? 'text-zinc-800 dark:text-zinc-200 font-medium' : '' }}">
            {{ $slot }}
        </span>
    @endif
</li>