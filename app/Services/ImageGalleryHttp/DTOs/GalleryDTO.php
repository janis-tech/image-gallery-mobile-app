<?php

namespace App\Services\ImageGalleryHttp\DTOs;

class GalleryDTO
{
    /**
     * Create a new GalleryDTO instance.
     *
     * @param string $id The gallery ID
     * @param string $name The gallery name
     * @param string $description The gallery description
     * @param string $created_at The creation timestamp
     * @param string $updated_at The last update timestamp
     * @param int $images_count The number of images in the gallery
     * @param array<string, string> $first_image The first image preview URLs
     */
    public function __construct(
        public readonly string $id,
        public readonly string $name,
        public readonly string $description,
        public readonly string $created_at,
        public readonly string $updated_at,
        public readonly int $images_count = 0,
        public readonly array $first_image = [],
    ) {
    }

    /**
     * Create a new GalleryDTO instance from an array.
     *
     * @param array<string, mixed> $data The gallery data
     * @return self
     */
    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['id'] ?? '',
            name: $data['name'] ?? '',
            description: $data['description'] ?? '',
            created_at: $data['created_at'] ?? '',
            updated_at: $data['updated_at'] ?? '',
            images_count: $data['images_count'] ?? $data['image_count'] ?? 0,
            first_image: $data['first_image'] ?? [],
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
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'images_count' => $this->images_count,
            'first_image' => $this->first_image,
        ];
    }
}
