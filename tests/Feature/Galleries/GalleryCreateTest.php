<?php

use App\Livewire\Galleries\GalleryCreate;
use App\Services\ImageGalleryHttp\ImageGalleryHttpServiceInterface;
use Livewire\Livewire;
use Mockery\MockInterface;

test('gallery creation page is displayed', function () {
    $this->actingAs($user = \App\Models\User::factory()->create());

    $this->get('/galleries/create')->assertOk()

        ->assertSeeLivewire('galleries.gallery-create')
        ->assertSee('Create New Gallery')
        ->assertSee('Name')
        ->assertSee('Description')
        ->assertSee('Create Gallery')
        ->assertSee('Cancel')
    ;
});

test('gallery can be created successfully', function () {
    $user = \App\Models\User::factory()->create();
    $this->actingAs($user);

    $mock_service = $this->mock(ImageGalleryHttpServiceInterface::class, function (MockInterface $mock) {
        $mock->shouldReceive('createGallery')
            ->once()
            ->withArgs(['Test Gallery', 'This is a test gallery'])
            ->andReturn([
                'success' => true,
                'errors' => [],
                'message' => 'Gallery created successfully',
            ]);
    });

    $response = Livewire::test(GalleryCreate::class)
        ->set('name', 'Test Gallery')
        ->set('description', 'This is a test gallery')
        ->call('createGallery');

    $response->assertRedirect(route('galleries.list'));
    $response->assertSessionHas('message', 'Gallery created successfully!');
});

test('validation errors are displayed on form submission', function () {
    $user = \App\Models\User::factory()->create();
    $this->actingAs($user);

    $response = Livewire::test(GalleryCreate::class)
        ->set('name', '')
        ->set('description', 'This is a test gallery')
        ->call('createGallery');

    $response->assertHasErrors(['name' => 'required']);
});

test('api validation errors are displayed on form submission', function () {
    $user = \App\Models\User::factory()->create();
    $this->actingAs($user);

    $mock_service = $this->mock(ImageGalleryHttpServiceInterface::class, function (MockInterface $mock) {
        $mock->shouldReceive('createGallery')
            ->once()
            ->andReturn([
                'success' => false,
                'errors' => ['name' => ['The name has already been taken.']],
                'message' => 'Validation failed',
            ]);
    });

    $response = Livewire::test(GalleryCreate::class)
        ->set('name', 'Test Gallery')
        ->set('description', 'This is a test gallery')
        ->call('createGallery');

    $response->assertHasErrors(['name' => 'The name has already been taken.']);
});


test('exception during gallery creation is handled gracefully', function () {
    $user = \App\Models\User::factory()->create();
    $this->actingAs($user);

    $mock_service = $this->mock(ImageGalleryHttpServiceInterface::class, function (MockInterface $mock) {
        $mock->shouldReceive('createGallery')
            ->once()
            ->andThrow(new \Exception('Connection error'));
    });

    $response = Livewire::test(GalleryCreate::class)
        ->set('name', 'Test Gallery')
        ->set('description', 'This is a test gallery')
        ->call('createGallery');

    $response->assertRedirect(route('galleries.list'));
    $response->assertSessionHas('error', 'An unexpected error occurred. Please try again later.');
});

test('description field is optional', function () {
    $user = \App\Models\User::factory()->create();
    $this->actingAs($user);

    $mock_service = $this->mock(ImageGalleryHttpServiceInterface::class, function (MockInterface $mock) {
        $mock->shouldReceive('createGallery')
            ->once()
            ->withArgs(['Test Gallery', ''])
            ->andReturn([
                'success' => true,
                'errors' => [],
                'message' => 'Gallery created successfully',
            ]);
    });

    $response = Livewire::test(GalleryCreate::class)
        ->set('name', 'Test Gallery')
        ->call('createGallery');

    $response->assertRedirect(route('galleries.list'));
    $response->assertSessionHas('message', 'Gallery created successfully!');
});
