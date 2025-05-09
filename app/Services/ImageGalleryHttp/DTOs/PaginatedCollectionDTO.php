<?php

namespace App\Services\ImageGalleryHttp\DTOs;

class PaginatedCollectionDTO extends AbstractDTO
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
     * @param string $itemType The type of items in the collection
     * @return static
     */
    public static function fromArray(array $data, string $itemType = ''): static
    {
        $items = [];
        
        if ($itemType && method_exists($itemType, 'fromArray')) {
            foreach ($data['data'] ?? [] as $item) {
                $items[] = $itemType::fromArray($item);
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