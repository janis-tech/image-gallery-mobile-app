<?php
namespace App\Services\ImageGalleryHttp;

interface ImageGalleryHttpServiceInterface
{
    /**
     * Fetch galleries from the image gallery API.
     * @param int|null $page The page number for pagination.
     * @param int|null $per_page The number of items per page.
     * @param string|null $search The search term to filter galleries.
     * @return array<mixed> An array of galleries.
     */
    public function getGalleries(?int $page, ?int $per_page, ?string $search ): array;

    /**
     * Get a specific gallery by its ID.
     * @param string $id The ID of the gallery to retrieve.
     * @return array<string, mixed>|null The gallery data or null if not found.
     */
    public function getGallery(string $id): ?array;

    /**
     * Create a new gallery.
     * @param string $name The name of the gallery.
     * @param string $description The description of the gallery.
     * @return array{success: bool, errors?: array<string, array<string>>} Returns success status and any validation errors.
     */
    public function createGallery(string $name, string $description): array;

    /**
     * Update an existing gallery.
     * @param string $id The ID of the gallery to update.
     * @param string $name The updated name of the gallery.
     * @param string $description The updated description of the gallery.
     * @return array{success: bool, errors?: array<string, array<string>>} Returns success status and any validation errors.
     */
    public function updateGallery(string $id, string $name, string $description): array;

    /**
     * Delete a gallery by its ID.
     * @param string $id The ID of the gallery to delete.
     * @return bool True if the gallery was deleted successfully, false otherwise.
     */
    public function deleteGallery(string $id): bool;


    /**
     * Fetch images from a specific gallery.
     * @param string $id The ID of the gallery to fetch images from.
     * @return array<mixed> An array of images in the gallery.
     */
    public function getGalleryImages(string $id): array;

}