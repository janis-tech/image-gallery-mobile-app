<?php

namespace App\Services\ImageGalleryHttp;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Log;

class ImageGalleryHttpService implements ImageGalleryHttpServiceInterface
{
    const BASE_URL = 'https://demo.janis-tech.dev/api/v1/';
    
    private Client $client;
    
    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => self::BASE_URL,
            'timeout'  => 10.0,
            'headers' => [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ],
        ]);
    }

    public function getGalleries(?int $page, ?int $per_page, ?string $search): array
    {
        $query_params = array_filter([
            'page' => $page,
            'per_page' => $per_page,
            'search' => $search,
        ]);

        try {
            $response = $this->client->request('GET', 'galleries', [
                'query' => $query_params
            ]);
            
            $data = json_decode($response->getBody()->getContents(), true);
            return $data['data'] ?? [];
            
        } catch (GuzzleException $e) {
            throw new \Exception('Error fetching galleries: ' . $e->getMessage());
        }
    }

    public function getGallery(string $id): ?array
    {
        try {
            $response = $this->client->request('GET', 'galleries/' . $id);
            
            $data = json_decode($response->getBody()->getContents(), true);
            return $data['data'] ?? null;
            
        } catch (GuzzleException $e) {
            if ($e->getCode() === 404) {
                return null;
            }
            throw new \Exception('Error fetching gallery: ' . $e->getMessage());
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
                'success' => true
            ];
            
        } catch (RequestException $e) {
            if ($e->getResponse() && $e->getResponse()->getStatusCode() === 422) {
                $responseBody = json_decode($e->getResponse()->getBody()->getContents(), true);
                
                return [
                    'success' => false,
                    'errors' => $responseBody['errors'] ?? [],
                    'message' => $responseBody['message'] ?? 'Validation failed'
                ];
            }
            
            Log::error('Error creating gallery', [
                'message' => $e->getMessage(),
                'code' => $e->getCode(),
                'response' => $e->getResponse() ? json_decode($e->getResponse()->getBody()->getContents(), true) : null
            ]);
            
            return [
                'success' => false,
                'message' => 'Failed to connect to the server'
            ];
        }
    }

    public function updateGallery(string $id, string $name, string $description): array
    {
        try {
            $response = $this->client->request('PUT', 'galleries/' . $id, [
                'json' => [
                    'name' => $name,
                    'description' => $description,
                ],
            ]);
            
            return [
                'success' => true
            ];
            
        } catch (RequestException $e) {
            if ($e->getResponse() && $e->getResponse()->getStatusCode() === 422) {
                $responseBody = json_decode($e->getResponse()->getBody()->getContents(), true);
                
                return [
                    'success' => false,
                    'errors' => $responseBody['errors'] ?? [],
                    'message' => $responseBody['message'] ?? 'Validation failed'
                ];
            }
            
            Log::error('Error updating gallery', [
                'id' => $id,
                'message' => $e->getMessage(),
                'code' => $e->getCode(),
                'response' => $e->getResponse() ? json_decode($e->getResponse()->getBody()->getContents(), true) : null
            ]);
            
            return [
                'success' => false,
                'message' => 'Failed to connect to the server'
            ];
        }
    }

    public function deleteGallery(string $id): bool
    {
        try {
            $response = $this->client->request('DELETE', 'galleries/' . $id);
            
            return $response->getStatusCode() === 204;
            
        } catch (GuzzleException $e) {
            throw new \Exception('Error deleting gallery: ' . $e->getMessage());
        }
    }
}
