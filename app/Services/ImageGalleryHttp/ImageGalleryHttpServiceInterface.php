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
}