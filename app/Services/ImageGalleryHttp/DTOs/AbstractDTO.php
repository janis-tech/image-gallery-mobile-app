<?php

namespace App\Services\ImageGalleryHttp\DTOs;

/**
 * Abstract Data Transfer Object base class.
 */
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
        throw new \LogicException(sprintf(
            'Class %s must implement its own fromArray() method',
            static::class
        ));
    }

    /**
     * Convert the DTO to an array.
     *
     * @return array<string, mixed>
     */
    abstract public function toArray(): array;
}