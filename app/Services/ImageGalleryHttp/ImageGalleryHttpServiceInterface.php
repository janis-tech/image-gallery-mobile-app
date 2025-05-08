<?php

namespace App\Services\ImageGalleryHttp;

interface ImageGalleryHttpServiceInterface
{
    /**
     * Set the entity ID to be used in the X-Entity-ID header.
     *
     * @param  string  $entity_id  The entity ID to use in requests.
     */
    public function setEntityId(?string $entity_id): void;

    /**
     * Fetch galleries from the image gallery API.
     *
     * @param  int|null  $page  The page number for pagination.
     * @param  int|null  $per_page  The number of items per page.
     * @param  string|null  $search  The search term to filter galleries.
     * @return array<mixed> An array of galleries.
     */
    public function getGalleries(?int $page, ?int $per_page, ?string $search): array;

    /**
     * Get a specific gallery by its ID.
     *
     * @param  string  $id  The ID of the gallery to retrieve.
     * @return array<string, mixed>|null The gallery data or null if not found.
     */
    public function getGallery(string $id): ?array;

    /**
     * Create a new gallery.
     *
     * @param  string  $name  The name of the gallery.
     * @param  string  $description  The description of the gallery.
     * @return array{success: bool, errors?: array<string, array<string>>} Returns success status and any validation errors.
     */
    public function createGallery(string $name, string $description): array;

    /**
     * Update an existing gallery.
     *
     * @param  string  $id  The ID of the gallery to update.
     * @param  string  $name  The updated name of the gallery.
     * @param  string  $description  The updated description of the gallery.
     * @return array{success: bool, errors?: array<string, array<string>>} Returns success status and any validation errors.
     */
    public function updateGallery(string $id, string $name, string $description): array;

    /**
     * Delete a gallery by its ID.
     *
     * @param  string  $id  The ID of the gallery to delete.
     * @return bool True if the gallery was deleted successfully, false otherwise.
     */
    public function deleteGallery(string $id): bool;

    /**
     * Fetch images from a specific gallery.
     *
     * @param  string  $id  The ID of the gallery to fetch images from.
     * @param  ?string  $search  The search term to filter images.
     * @param  ?int  $perPage  Number of items per page for pagination.
     * @param  ?int  $page  Current page number for pagination.
     * @return array{data: array, pagination: array} An array containing images data and pagination metadata.
     */
    public function getGalleryImages(string $id, ?string $search = null, ?int $perPage = null, ?int $page = null);

    /**
     * Fetch a specific image from a gallery.
     *
     * @param  string  $gallery_id  The ID of the gallery containing the image.
     * @param  string  $image_id  The ID of the image to fetch.
     * @return array<string, mixed> An array containing the image data.
     */
    public function getGalleryImage(string $gallery_id, string $image_id): array;

    /**
     * Update a gallery image.
     *
     * @param  string  $gallery_id  The ID of the gallery containing the image.
     * @param  string  $image_id  The ID of the image to update.
     * @param  string  $title  The new title for the image.
     * @param  string  $alt_text  The new alt text for the image.
     * @param  string  $description  The new description for the image.
     * @return array{success: bool, errors?: array<string, array<string>>} Returns success status and any validation errors.
     */
    public function updateGalleryImage(string $gallery_id, string $image_id, string $title, string $alt_text, string $description): array;

    /**
     * Upload an image to a gallery.
     *
     * @param  string  $gallery_id  The ID of the gallery to add the image to.
     * @param  string  $file_path  The path to the image file to upload.
     * @param  ?string  $title  The title for the image (optional).
     * @param  ?string  $file_name  The file name for the image (optional).
     * @param  ?string  $description  The description for the image (optional).
     * @param  ?string  $alt_text  The alt text for the image (optional).
     * @return array{success: bool, errors?: array<string, array<string>>, message?: string} Returns success status and any validation errors.
     */
    public function uploadGalleryImage(string $gallery_id, string $file_path, ?string $title = null, ?string $file_name = null, ?string $description = null, ?string $alt_text = null): array;

    /**
     * Delete a specific image from a gallery.
     *
     * @param  string  $gallery_id  The ID of the gallery containing the image.
     * @param  string  $image_id  The ID of the image to delete.
     * @return bool True if the image was deleted successfully, false otherwise.
     */
    public function deleteGalleryImage(string $gallery_id, string $image_id): bool;
}
