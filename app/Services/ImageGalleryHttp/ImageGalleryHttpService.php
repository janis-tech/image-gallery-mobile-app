<?php

namespace App\Services\ImageGalleryHttp;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class ImageGalleryHttpService implements ImageGalleryHttpServiceInterface
{
    const BASE_URL = 'https://demo.janis-tech.dev/api/v1/';
    
    private Client $client;
    
    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => self::BASE_URL,
            'timeout'  => 10.0,
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
}
