<?php

namespace App\Livewire\Components;

use App\Services\ImageGalleryHttp\DTOs\PaginationDTO;
use Livewire\Component;

class ArrayPagination extends Component
{
    /**
     * @var array<mixed>|PaginationDTO
     */
    public $pagination = [];

    public int $current_page = 1;

    public string $page_name = 'page';

    /**
     * @param array<mixed> $pagination
     * @param int $current_page
     * @param string $page_name
     * @return void
     */
    public function mount(array $pagination, int $current_page = 1, string $page_name = 'page'): void
    {
        $this->pagination = $pagination;
        $this->current_page = $current_page;
        $this->page_name = $page_name;
    }

    public function updating(string $name, string $value): void
    {
    }

    /**
     * Go to the previous page and emit an event
     */
    public function previousPage(): void
    {
        if ($this->current_page > 1) {
            $this->dispatch(
                'pageChange',
                page: $this->current_page - 1,
                page_name: $this->page_name
            );
        }
    }

    public function nextPage(): void
    {
        if ($this->current_page < $this->getLastPage()) {
            $this->dispatch(
                'pageChange',
                page: $this->current_page + 1,
                page_name: $this->page_name
            );
        }
    }

    public function goToPage(int $page): void
    {
        if ($page >= 1 && $page <= $this->getLastPage() && $page !== $this->current_page) {
            $this->dispatch(
                'pageChange',
                page: $page,
                page_name: $this->page_name
            );
        }
    }

    public function render(): \Illuminate\Contracts\View\View
    {
        return view('livewire.components.array-pagination');
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
}
