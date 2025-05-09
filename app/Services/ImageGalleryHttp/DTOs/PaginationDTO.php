<?php

namespace App\Services\ImageGalleryHttp\DTOs;

class PaginationDTO extends AbstractDTO
{
    /**
     * Create a new PaginationDTO instance.
     *
     * @param int $total The total number of items
     * @param int $per_page The number of items per page
     * @param int $current_page The current page number
     * @param int $last_page The last page number
     * @param int $from The starting item index
     * @param int $to The ending item index
     */
    public function __construct(
        public readonly int $total,
        public readonly int $per_page,
        public readonly int $current_page,
        public readonly int $last_page,
        public readonly int $from,
        public readonly int $to,
    ) {}

    /**
     * Create a new PaginationDTO instance from an array.
     *
     * @param array<string, mixed> $data The pagination data
     * @return static
     */
    public static function fromArray(array $data): static
    {
        return new static(
            total: $data['total'] ?? 0,
            per_page: $data['per_page'] ?? 15,
            current_page: $data['current_page'] ?? 1,
            last_page: $data['last_page'] ?? 1,
            from: $data['from'] ?? 1,
            to: $data['to'] ?? 1,
        );
    }

    /**
     * Convert the DTO to an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'total' => $this->total,
            'per_page' => $this->per_page,
            'current_page' => $this->current_page,
            'last_page' => $this->last_page,
            'from' => $this->from,
            'to' => $this->to,
        ];
    }
}