<?php

namespace App\Livewire\Components;

use Livewire\Component;

class ArrayPagination extends Component
{
    public array $pagination = [];
    public int $currentPage = 1;
    public string $pageName = 'page';

    public function mount($pagination, $currentPage = 1, $pageName = 'page')
    {
        $this->pagination = $pagination;
        $this->currentPage = $currentPage;
        $this->pageName = $pageName;
    }
    
    public function updating($name, $value)
    {

    }

    /**
     * Go to the previous page and emit an event
     */
    public function previousPage()
    {
        if ($this->currentPage > 1) {
            $this->dispatch('pageChange', 
                page: $this->currentPage - 1,
                pageName: $this->pageName
            );
        }
    }

    public function nextPage()
    {
        if ($this->currentPage < ($this->pagination['last_page'] ?? 1)) {
            $this->dispatch('pageChange', 
                page: $this->currentPage + 1,
                pageName: $this->pageName
            );
        }
    }

    public function goToPage($page)
    {
        if ($page >= 1 && $page <= ($this->pagination['last_page'] ?? 1) && $page != $this->currentPage) {
            $this->dispatch('pageChange', 
                page: $page,
                pageName: $this->pageName
            );
        }
    }

    public function render()
    {
        return view('livewire.components.array-pagination');
    }
}
