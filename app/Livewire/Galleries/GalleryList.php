<?php

namespace App\Livewire\Galleries;

use Livewire\Component;

class GalleryList extends Component
{
    public $galleries = [];
    
    public function mount()
    {
        $this->galleries = [
            [
                'id' => 1,
                'title' => 'Summer Vacation 2024',
                'description' => 'Highlights from our trip to the coast',
                'cover_image' => 'https://images.unsplash.com/photo-1507525428034-b723cf961d3e',
                'images_count' => 24
            ],
            [
                'id' => 2,
                'title' => 'Family Reunion',
                'description' => 'Everyone together after 2 years',
                'cover_image' => 'https://images.unsplash.com/photo-1511895426328-dc8714191300',
                'images_count' => 42
            ],
            [
                'id' => 3,
                'title' => 'Mountain Hiking',
                'description' => 'Weekend adventure in the mountains',
                'cover_image' => 'https://images.unsplash.com/photo-1464822759023-fed622ff2c3b',
                'images_count' => 16
            ],
            [
                'id' => 4,
                'title' => 'City Lights',
                'description' => 'Urban photography collection',
                'cover_image' => 'https://images.unsplash.com/photo-1519501025264-65ba15a82390',
                'images_count' => 31
            ],
            [
                'id' => 5,
                'title' => 'Nature Close-ups',
                'description' => 'Macro photography of plants and insects',
                'cover_image' => 'https://images.unsplash.com/photo-1500382017468-9049fed747ef',
                'images_count' => 18
            ],
            [
                'id' => 6,
                'title' => 'Food Festival',
                'description' => 'Delicious moments from the annual food festival',
                'cover_image' => 'https://images.unsplash.com/photo-1504674900247-0877df9cc836',
                'images_count' => 27
            ],
        ];
    }

    public function render()
    {
        return view('livewire.galleries.gallery-list');
    }
}
