<?php

namespace App\Services\ImageGalleryHttp\DTOs;

abstract class AbstractDTO
{
    /**
     * Create a new DTO instance from array data.
     *
     * @param array<string, mixed> $data The data to create the DTO from
     * @return static
     */
    public static function fromArray(array $data): static
    {
        return new static(...$data);
    }

    /**
     * Convert the DTO to an array.
     *
     * @return array<string, mixed>
     */
    abstract public function toArray(): array;
}