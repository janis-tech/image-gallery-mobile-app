<?php

namespace App\Livewire\Components;

use App\Services\ImageGalleryHttp\DTOs\PaginationDTO;
use Livewire\Component;

class ArrayPagination extends Component
{
    /**
     * @var array|PaginationDTO
     */
    public $pagination = [];

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
        if ($this->current_page < $this->getLastPage()) {
            $this->dispatch('pageChange',
                page: $this->current_page + 1,
                page_name: $this->page_name
            );
        }
    }

    public function goToPage($page)
    {
        if ($page >= 1 && $page <= $this->getLastPage() && $page != $this->current_page) {
            $this->dispatch('pageChange',
                page: $page,
                page_name: $this->page_name
            );
        }
    }

    /**
     * Get the last page number regardless of pagination type
     * 
     * @return int
     */
    private function getLastPage(): int
    {
        if ($this->pagination instanceof PaginationDTO) {
            return $this->pagination->last_page;
        }
        
        return $this->pagination['last_page'] ?? 1;
    }

    public function render()
    {
        return view('livewire.components.array-pagination');
    }
}
