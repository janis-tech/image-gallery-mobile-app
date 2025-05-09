<?php

namespace App\Services\ImageGalleryHttp;

use App\Services\ImageGalleryHttp\DTOs\GalleryDTO;
use App\Services\ImageGalleryHttp\DTOs\GalleryImageDTO;
use App\Services\ImageGalleryHttp\DTOs\PaginatedCollectionDTO;
use App\Services\ImageGalleryHttp\DTOs\PaginationDTO;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Log;
use InvalidArgumentException;

class ImageGalleryHttpService implements ImageGalleryHttpServiceInterface
{
    const BASE_URL = 'https://demo.janis-tech.dev/api/v1/';

    private Client $client;

    private string $entity_id;

    public function __construct(?string $entity_id)
    {
        $this->entity_id = $entity_id;
        $this->initClient();
    }

    /**
     * Set the entity ID to be used in the X-Entity-ID header.
     *
     * @param  ?string  $entity_id  The entity ID to use in requests.
     */
    public function setEntityId(?string $entity_id): void
    {
        if ($entity_id === null) {
            throw new InvalidArgumentException('Entity ID cannot be null');
        }

        $this->entity_id = $entity_id;
        $this->initClient();
    }

    /**
     * Initialize the HTTP client with current configuration.
     */
    private function initClient(): void
    {
        $this->client = new Client([
            'base_uri' => self::BASE_URL,
            'timeout' => 30.0,
            'headers' => [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
                'X-Entity-ID' => $this->entity_id,
            ],
        ]);
    }

    public function getGalleries(?int $page, ?int $per_page, ?string $search): PaginatedCollectionDTO
    {
        $query_params = array_filter([
            'page' => $page,
            'per_page' => $per_page,
            'search' => $search,
        ]);

        try {
            $response = $this->client->request('GET', 'galleries', [
                'query' => $query_params,
            ]);

            $response_data = json_decode($response->getBody()->getContents(), true);
            
            $pagination = [
                'total' => $response_data['meta']['total'] ?? count($response_data['data'] ?? []),
                'per_page' => $response_data['meta']['per_page'] ?? $per_page ?? 15,
                'current_page' => $response_data['meta']['current_page'] ?? $page ?? 1,
                'last_page' => $response_data['meta']['last_page'] ?? 1,
                'from' => $response_data['meta']['from'] ?? 1,
                'to' => $response_data['meta']['to'] ?? count($response_data['data'] ?? []),
            ];

            $galleries = [];
            foreach ($response_data['data'] ?? [] as $gallery) {
                $galleries[] = GalleryDTO::fromArray($gallery);
            }

            return new PaginatedCollectionDTO(
                $galleries,
                PaginationDTO::fromArray($pagination)
            );

        } catch (GuzzleException $e) {
            throw new \Exception('Error fetching galleries: '.$e->getMessage());
        }
    }

    public function getGallery(string $id): ?GalleryDTO
    {
        try {
            $response = $this->client->request('GET', 'galleries/'.$id);

            $data = json_decode($response->getBody()->getContents(), true);

            if (isset($data['data'])) {
                return GalleryDTO::fromArray($data['data']);
            }

            return null;

        } catch (GuzzleException $e) {
            if ($e->getCode() === 404) {
                return null;
            }
            throw new \Exception('Error fetching gallery: '.$e->getMessage());
        }
    }

    public function createGallery(string $name, string $description): array
    {
        try {
            $this->client->post('galleries', [
                'json' => [
                    'name' => $name,
                    'description' => $description,
                ],
            ]);

            return [
                'success' => true,
            ];

        } catch (RequestException $e) {
            if ($e->getResponse() && $e->getResponse()->getStatusCode() === 422) {
                $response_body = json_decode($e->getResponse()->getBody()->getContents(), true);

                return [
                    'success' => false,
                    'errors' => $response_body['errors'] ?? [],
                    'message' => $response_body['message'] ?? 'Validation failed',
                ];
            }

            Log::error('Error creating gallery', [
                'message' => $e->getMessage(),
                'code' => $e->getCode(),
                'response' => $e->getResponse() ? json_decode($e->getResponse()->getBody()->getContents(), true) : null,
            ]);

            return [
                'success' => false,
                'message' => 'Failed to connect to the server',
            ];
        }
    }

    public function updateGallery(string $id, string $name, string $description): array
    {
        try {
            $response = $this->client->request('PUT', 'galleries/'.$id, [
                'json' => [
                    'name' => $name,
                    'description' => $description,
                ],
            ]);

            return [
                'success' => true,
            ];

        } catch (RequestException $e) {
            if ($e->getResponse() && $e->getResponse()->getStatusCode() === 422) {
                $response_body = json_decode($e->getResponse()->getBody()->getContents(), true);

                return [
                    'success' => false,
                    'errors' => $response_body['errors'] ?? [],
                    'message' => $response_body['message'] ?? 'Validation failed',
                ];
            }

            Log::error('Error updating gallery', [
                'id' => $id,
                'message' => $e->getMessage(),
                'code' => $e->getCode(),
                'response' => $e->getResponse() ? json_decode($e->getResponse()->getBody()->getContents(), true) : null,
            ]);

            return [
                'success' => false,
                'message' => 'Failed to connect to the server',
            ];
        }
    }

    public function deleteGallery(string $id): bool
    {
        try {
            $response = $this->client->request('DELETE', 'galleries/'.$id);

            return $response->getStatusCode() === 204;

        } catch (GuzzleException $e) {
            throw new \Exception('Error deleting gallery: '.$e->getMessage());
        }
    }

    public function getGalleryImages(string $id, ?string $search = null, ?int $per_page = null, ?int $page = null): PaginatedCollectionDTO
    {
        try {
            $query_params = [];

            if ($search) {
                $query_params['vector_search'] = $search;
            }

            if ($per_page) {
                $query_params['per_page'] = $per_page;
            }

            if ($page) {
                $query_params['page'] = $page;
            }

            $response = $this->client->request('GET', 'galleries/'.$id.'/images', [
                'query' => $query_params,
            ]);

            $response_data = json_decode($response->getBody()->getContents(), true);
            
            $pagination = [
                'total' => $response_data['meta']['total'] ?? count($response_data['data'] ?? []),
                'per_page' => $response_data['meta']['per_page'] ?? $per_page ?? 12,
                'current_page' => $response_data['meta']['current_page'] ?? $page ?? 1,
                'last_page' => $response_data['meta']['last_page'] ?? 1,
                'from' => $response_data['meta']['from'] ?? 1,
                'to' => $response_data['meta']['to'] ?? count($response_data['data'] ?? []),
            ];

            $images = [];
            foreach ($response_data['data'] ?? [] as $image) {
                $images[] = GalleryImageDTO::fromArray($image);
            }

            return new PaginatedCollectionDTO(
                $images,
                PaginationDTO::fromArray($pagination)
            );

        } catch (GuzzleException $e) {
            throw new \Exception('Error fetching gallery images: '.$e->getMessage());
        }
    }

    public function getGalleryImage(string $gallery_id, string $image_id): GalleryImageDTO
    {
        try {
            $response = $this->client->request('GET', 'galleries/'.$gallery_id.'/images/'.$image_id);

            $data = json_decode($response->getBody()->getContents(), true);

            return GalleryImageDTO::fromArray($data['data'] ?? []);

        } catch (GuzzleException $e) {
            throw new \Exception('Error fetching image: '.$e->getMessage());
        }
    }

    public function updateGalleryImage(string $gallery_id, string $image_id, string $title, string $alt_text, string $description): array
    {
        try {
            $response = $this->client->request('PUT', 'galleries/'.$gallery_id.'/images/'.$image_id, [
                'json' => [
                    'title' => $title,
                    'alt_text' => $alt_text,
                    'description' => $description,
                ],
            ]);

            return [
                'success' => true,
            ];

        } catch (RequestException $e) {
            if ($e->getResponse() && $e->getResponse()->getStatusCode() === 422) {
                $response_body = json_decode($e->getResponse()->getBody()->getContents(), true);

                return [
                    'success' => false,
                    'errors' => $response_body['errors'] ?? [],
                    'message' => $response_body['message'] ?? 'Validation failed',
                ];
            }

            Log::error('Error updating gallery image', [
                'gallery_id' => $gallery_id,
                'image_id' => $image_id,
                'message' => $e->getMessage(),
                'code' => $e->getCode(),
                'response' => $e->getResponse() ? json_decode($e->getResponse()->getBody()->getContents(), true) : null,
            ]);

            return [
                'success' => false,
                'message' => 'Failed to connect to the server',
            ];
        }
    }

    public function uploadGalleryImage(string $gallery_id, string $file_path, ?string $title = null, ?string $file_name = null, ?string $description = null, ?string $alt_text = null): array
    {
        try {
            $multipart = [
                [
                    'name' => 'image',
                    'contents' => fopen($file_path, 'r'),
                    'filename' => $file_name ?? basename($file_path),
                ],
            ];

            // Add optional fields if provided
            if ($title) {
                $multipart[] = [
                    'name' => 'title',
                    'contents' => $title,
                ];
            }

            if ($file_name) {
                $multipart[] = [
                    'name' => 'file_name',
                    'contents' => $file_name,
                ];
            }

            if ($description) {
                $multipart[] = [
                    'name' => 'description',
                    'contents' => $description,
                ];
            }

            if ($alt_text) {
                $multipart[] = [
                    'name' => 'alt_text',
                    'contents' => $alt_text,
                ];
            }

            $response = $this->client->request('POST', "galleries/{$gallery_id}/images", [
                'multipart' => $multipart,
            ]);

            $data = json_decode($response->getBody()->getContents(), true);
            $image_dto = isset($data['data']) ? GalleryImageDTO::fromArray($data['data']) : null;

            return [
                'success' => true,
                'data' => $image_dto,
            ];

        } catch (RequestException $e) {
            if ($e->getResponse() && $e->getResponse()->getStatusCode() === 422) {
                $response_body = json_decode($e->getResponse()->getBody()->getContents(), true);

                return [
                    'success' => false,
                    'errors' => $response_body['errors'] ?? [],
                    'message' => $response_body['message'] ?? 'Validation failed',
                ];
            }

            Log::error('Error uploading image to gallery', [
                'gallery_id' => $gallery_id,
                'message' => $e->getMessage(),
                'code' => $e->getCode(),
                'response' => $e->getResponse() ? json_decode($e->getResponse()->getBody()->getContents(), true) : null,
            ]);

            return [
                'success' => false,
                'message' => 'Failed to upload image. Please try again later.',
            ];
        } catch (\Exception $e) {
            Log::error('Error uploading image to gallery', [
                'gallery_id' => $gallery_id,
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return [
                'success' => false,
                'message' => 'An unexpected error occurred. Please try again later.',
            ];
        }
    }

    public function deleteGalleryImage(string $gallery_id, string $image_id): bool
    {
        try {
            $response = $this->client->request('DELETE', 'galleries/'.$gallery_id.'/images/'.$image_id);

            return $response->getStatusCode() === 204;

        } catch (GuzzleException $e) {
            Log::error('Error deleting gallery image', [
                'gallery_id' => $gallery_id,
                'image_id' => $image_id,
                'message' => $e->getMessage(),
                'code' => $e->getCode(),
            ]);

            return false;
        }
    }
}
