<?php

namespace App\Services\ImageGalleryHttp\DTOs;

class PaginatedCollectionDTO
{
    /**
     * Create a new PaginatedCollectionDTO instance.
     *
     * @param array<int, mixed> $data The collection data
     * @param PaginationDTO $pagination The pagination information
     */
    public function __construct(
        public readonly array $data,
        public readonly PaginationDTO $pagination,
    ) {}

    /**
     * Create a new PaginatedCollectionDTO instance from an array.
     *
     * @param array<string, mixed> $data The collection data
     * @param string $item_type The type of items in the collection
     * @return self
     */
    public static function fromArray(array $data, string $item_type = ''): self
    {
        $items = [];
        
        if ($item_type && method_exists($item_type, 'fromArray')) {
            foreach ($data['data'] ?? [] as $item) {
                $items[] = $item_type::fromArray($item);
            }
        } else {
            $items = $data['data'] ?? [];
        }

        return new self(
            data: $items,
            pagination: PaginationDTO::fromArray($data['pagination'] ?? $data['meta'] ?? []),
        );
    }

    /**
     * Convert the DTO to an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        $items = [];
        
        foreach ($this->data as $item) {
            if (method_exists($item, 'toArray')) {
                $items[] = $item->toArray();
            } else {
                $items[] = $item;
            }
        }

        return [
            'data' => $items,
            'pagination' => $this->pagination->toArray(),
        ];
    }
}