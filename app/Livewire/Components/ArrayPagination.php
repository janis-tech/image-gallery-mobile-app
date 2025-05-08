<?php

namespace App\Livewire\Components;

use Livewire\Component;

class ArrayPagination extends Component
{
    public array $pagination = [];

    public int $current_page = 1;

    public string $page_name = 'page';

    public function mount($pagination, $current_page = 1, $page_name = 'page')
    {
        $this->pagination = $pagination;
        $this->current_page = $current_page;
        $this->page_name = $page_name;
    }

    public function updating($name, $value) {}

    /**
     * Go to the previous page and emit an event
     */
    public function previousPage()
    {
        if ($this->current_page > 1) {
            $this->dispatch('pageChange',
                page: $this->current_page - 1,
                page_name: $this->page_name
            );
        }
    }

    public function nextPage()
    {
        if ($this->current_page < ($this->pagination['last_page'] ?? 1)) {
            $this->dispatch('pageChange',
                page: $this->current_page + 1,
                page_name: $this->page_name
            );
        }
    }

    public function goToPage($page)
    {
        if ($page >= 1 && $page <= ($this->pagination['last_page'] ?? 1) && $page != $this->current_page) {
            $this->dispatch('pageChange',
                page: $page,
                page_name: $this->page_name
            );
        }
    }

    public function render()
    {
        return view('livewire.components.array-pagination');
    }
}
