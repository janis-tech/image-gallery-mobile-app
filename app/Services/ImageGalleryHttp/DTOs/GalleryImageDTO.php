<?php

namespace App\Services\ImageGalleryHttp\DTOs;

class GalleryImageDTO
{
    /**
     * Create a new GalleryImageDTO instance.
     *
     * @param string $id The image ID
     * @param string $gallery_id The gallery ID
     * @param string $title The image title
     * @param string $description The image description
     * @param string $alt_text The image alt text
     * @param string|null $file_name The image file name
     * @param string|null $original_filename The original image file name
     * @param int|null $file_size The image file size
     * @param string|null $mime_type The image MIME type
     * @param int|null $width The image width
     * @param int|null $height The image height
     * @param string|null $file_path The image file path
     * @param string|null $file_url The image file URL
     * @param string|null $generated_caption The generated image caption
     * @param array<string, string>|null $presets The image presets
     * @param string $created_at The creation timestamp
     * @param string $updated_at The last update timestamp
     */
    public function __construct(
        public readonly string $id,
        public readonly string $gallery_id,
        public readonly string $title,
        public readonly string $description,
        public readonly string $alt_text,
        public readonly ?string $file_name,
        public readonly ?string $original_filename,
        public readonly ?int $file_size,
        public readonly ?string $mime_type,
        public readonly ?int $width,
        public readonly ?int $height,
        public readonly ?string $file_path,
        public readonly ?string $file_url,
        public readonly ?string $generated_caption,
        public readonly ?array $presets,
        public readonly string $created_at,
        public readonly string $updated_at,
    ) {}

    /**
     * Create a new GalleryImageDTO instance from an array.
     *
     * @param array<string, mixed> $data The image data
     * @return self
     */
    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['id'] ?? '',
            gallery_id: $data['gallery_id'] ?? '',
            title: $data['title'] ?? '',
            description: $data['description'] ?? '',
            alt_text: $data['alt_text'] ?? '',
            file_name: $data['file_name'] ?? null,
            original_filename: $data['original_filename'] ?? null,
            file_size: is_numeric($data['file_size'] ?? null) ? (int) $data['file_size'] : null,
            mime_type: $data['mime_type'] ?? null,
            width: is_numeric($data['width'] ?? null) ? (int) $data['width'] : null,
            height: is_numeric($data['height'] ?? null) ? (int) $data['height'] : null,
            file_path: $data['file_path'] ?? null,
            file_url: $data['file_url'] ?? null,
            generated_caption: $data['generated_caption'] ?? null,
            presets: $data['presets'] ?? null,
            created_at: $data['created_at'] ?? '',
            updated_at: $data['updated_at'] ?? '',
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
            'gallery_id' => $this->gallery_id,
            'title' => $this->title,
            'description' => $this->description,
            'alt_text' => $this->alt_text,
            'file_name' => $this->file_name,
            'original_filename' => $this->original_filename,
            'file_size' => $this->file_size,
            'mime_type' => $this->mime_type,
            'width' => $this->width,
            'height' => $this->height,
            'file_path' => $this->file_path,
            'file_url' => $this->file_url,
            'generated_caption' => $this->generated_caption,
            'presets' => $this->presets,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}