<?php

use App\Livewire\Galleries\GalleryEdit;
use App\Services\ImageGalleryHttp\DTOs\GalleryDTO;
use App\Services\ImageGalleryHttp\ImageGalleryHttpServiceInterface;
use Livewire\Livewire;
use Mockery\MockInterface;

test('gallery edit page shows gallery data', function () {
    $user = \App\Models\User::factory()->create();
    $this->actingAs($user);

    $uuid = str()->uuid()->toString();

    $gallery_data = [
        'id' => $uuid,
        'name' => 'Test Gallery',
        'description' => 'Gallery Description',
        'created_at' => '2025-01-01T00:00:00Z',
        'updated_at' => '2025-01-01T00:00:00Z',
    ];

    $gallery_dto = Mockery::mock(GalleryDTO::class);
    $gallery_dto->shouldReceive('toArray')->andReturn($gallery_data);

    $this->mock(ImageGalleryHttpServiceInterface::class, function (MockInterface $mock) use ($gallery_dto, $uuid) {
        $mock->shouldReceive('getGallery')
            ->once()
            ->with($uuid)
            ->andReturn($gallery_dto);
    });

    $component = Livewire::test(GalleryEdit::class, ['id' => $uuid]);

    expect($component->get('name'))->toBe('Test Gallery');
    expect($component->get('description'))->toBe('Gallery Description');
});

test('redirects to galleries list when gallery is not found', function () {
    $user = \App\Models\User::factory()->create();
    $this->actingAs($user);

    $this->mock(ImageGalleryHttpServiceInterface::class, function (MockInterface $mock) {
        $mock->shouldReceive('getGallery')
            ->once()
            ->with('non-existent')
            ->andReturn(null);
    });

    $component = Livewire::test(GalleryEdit::class, ['id' => 'non-existent']);

    $component->assertRedirect(route('galleries.list'));
    $component->assertSessionHas('error', 'Gallery not found.');
});

test('gallery can be updated successfully', function () {
    $user = \App\Models\User::factory()->create();
    $this->actingAs($user);

    $uuid = str()->uuid()->toString();

    $gallery_data = [
        'id' => $uuid,
        'name' => 'Original Gallery',
        'description' => 'Original Description',
        'created_at' => '2025-01-01T00:00:00Z',
        'updated_at' => '2025-01-01T00:00:00Z',
    ];

    $gallery_dto = Mockery::mock(GalleryDTO::class);
    $gallery_dto->shouldReceive('toArray')->andReturn($gallery_data);

    $this->mock(ImageGalleryHttpServiceInterface::class, function (MockInterface $mock) use ($gallery_dto, $uuid) {
        $mock->shouldReceive('getGallery')
            ->once()
            ->with($uuid)
            ->andReturn($gallery_dto);

        $mock->shouldReceive('updateGallery')
            ->once()
            ->withArgs([$uuid, 'Updated Gallery', 'Updated Description'])
            ->andReturn([
                'success' => true,
                'errors' => [],
                'message' => 'Gallery updated successfully',
            ]);
    });

    $component = Livewire::test(GalleryEdit::class, ['id' => $uuid])
        ->set('name', 'Updated Gallery')
        ->set('description', 'Updated Description')
        ->call('updateGallery');

    $component->assertRedirect(route('galleries.list'));
    $component->assertSessionHas('message', 'Gallery updated successfully!');
});

test('validation errors are displayed on update', function () {
    $user = \App\Models\User::factory()->create();
    $this->actingAs($user);

    $uuid = str()->uuid()->toString();

    $gallery_data = [
        'id' => $uuid,
        'name' => 'Original Gallery',
        'description' => 'Original Description',
        'created_at' => '2025-01-01T00:00:00Z',
        'updated_at' => '2025-01-01T00:00:00Z',
    ];

    $gallery_dto = Mockery::mock(GalleryDTO::class);
    $gallery_dto->shouldReceive('toArray')->andReturn($gallery_data);

    $this->mock(ImageGalleryHttpServiceInterface::class, function (MockInterface $mock) use ($gallery_dto, $uuid) {
        $mock->shouldReceive('getGallery')
            ->once()
            ->with($uuid)
            ->andReturn($gallery_dto);
    });

    $component = Livewire::test(GalleryEdit::class, ['id' => $uuid])
        ->set('name', str()->random(260)) // Exceeding max length
        ->call('updateGallery');

    $component->assertHasErrors(['name' => 'max']);
});

test('api validation errors are displayed on update', function () {
    $user = \App\Models\User::factory()->create();
    $this->actingAs($user);

    $uuid = str()->uuid()->toString();

    $gallery_data = [
        'id' => $uuid,
        'name' => 'Original Gallery',
        'description' => 'Original Description',
        'created_at' => '2025-01-01T00:00:00Z',
        'updated_at' => '2025-01-01T00:00:00Z',
    ];

    $gallery_dto = Mockery::mock(GalleryDTO::class);
    $gallery_dto->shouldReceive('toArray')->andReturn($gallery_data);

    $this->mock(ImageGalleryHttpServiceInterface::class, function (MockInterface $mock) use ($gallery_dto, $uuid) {
        $mock->shouldReceive('getGallery')
            ->once()
            ->with($uuid)
            ->andReturn($gallery_dto);

        $mock->shouldReceive('updateGallery')
            ->once()
            ->andReturn([
                'success' => false,
                'errors' => ['name' => ['The name has already been taken.']],
                'message' => 'Validation failed',
            ]);
    });

    $component = Livewire::test(GalleryEdit::class, ['id' => $uuid])
        ->set('name', 'Duplicate Gallery')
        ->call('updateGallery');

    $component->assertHasErrors(['name' => 'The name has already been taken.']);
});

test('exception during gallery update is handled gracefully', function () {
    $user = \App\Models\User::factory()->create();
    $this->actingAs($user);

    $uuid = str()->uuid()->toString();

    $gallery_data = [
        'id' => $uuid,
        'name' => 'Original Gallery',
        'description' => 'Original Description',
        'created_at' => '2025-01-01T00:00:00Z',
        'updated_at' => '2025-01-01T00:00:00Z',
    ];

    $gallery_dto = Mockery::mock(GalleryDTO::class);
    $gallery_dto->shouldReceive('toArray')->andReturn($gallery_data);

    $this->mock(ImageGalleryHttpServiceInterface::class, function (MockInterface $mock) use ($gallery_dto, $uuid) {
        $mock->shouldReceive('getGallery')
            ->once()
            ->with($uuid)
            ->andReturn($gallery_dto);

        $mock->shouldReceive('updateGallery')
            ->once()
            ->andThrow(new \Exception('Connection error'));
    });

    $component = Livewire::test(GalleryEdit::class, ['id' => $uuid])
        ->set('name', 'Updated Gallery')
        ->call('updateGallery');

    $component->assertRedirect(route('galleries.list'));
    $component->assertSessionHas('error', 'An unexpected error occurred. Please try again later.');
});

test('exception during gallery load is handled gracefully', function () {
    $user = \App\Models\User::factory()->create();
    $this->actingAs($user);

    $uuid = str()->uuid()->toString();

    $this->mock(ImageGalleryHttpServiceInterface::class, function (MockInterface $mock) use ($uuid) {
        $mock->shouldReceive('getGallery')
            ->once()
            ->with($uuid)
            ->andThrow(new \Exception('Connection error'));
    });

    $component = Livewire::test(GalleryEdit::class, ['id' => $uuid]);

    $component->assertRedirect(route('galleries.list'));
    $component->assertSessionHas('error', 'Failed to load gallery. Please try again later.');
});

test('description field is optional on update', function () {
    $user = \App\Models\User::factory()->create();
    $this->actingAs($user);

    $uuid = str()->uuid()->toString();

    $gallery_data = [
        'id' => $uuid,
        'name' => 'Original Gallery',
        'description' => 'Original Description',
        'created_at' => '2025-01-01T00:00:00Z',
        'updated_at' => '2025-01-01T00:00:00Z',
    ];

    $gallery_dto = Mockery::mock(GalleryDTO::class);
    $gallery_dto->shouldReceive('toArray')->andReturn($gallery_data);

    $this->mock(ImageGalleryHttpServiceInterface::class, function (MockInterface $mock) use ($gallery_dto, $uuid) {
        $mock->shouldReceive('getGallery')
            ->once()
            ->with($uuid)
            ->andReturn($gallery_dto);

        $mock->shouldReceive('updateGallery')
            ->once()
            ->withArgs([$uuid, 'Updated Gallery', ''])
            ->andReturn([
                'success' => true,
                'errors' => [],
                'message' => 'Gallery updated successfully',
            ]);
    });

    $component = Livewire::test(GalleryEdit::class, ['id' => $uuid])
        ->set('name', 'Updated Gallery')
        ->set('description', '')
        ->call('updateGallery');

    $component->assertRedirect(route('galleries.list'));
    $component->assertSessionHas('message', 'Gallery updated successfully!');
});
test('name field is optional on update', function () {
    $user = \App\Models\User::factory()->create();
    $this->actingAs($user);

    $uuid = str()->uuid()->toString();

    $gallery_data = [
        'id' => $uuid,
        'name' => 'Original Gallery',
        'description' => 'Original Description',
        'created_at' => '2025-01-01T00:00:00Z',
        'updated_at' => '2025-01-01T00:00:00Z',
    ];

    $gallery_dto = Mockery::mock(GalleryDTO::class);
    $gallery_dto->shouldReceive('toArray')->andReturn($gallery_data);

    $this->mock(ImageGalleryHttpServiceInterface::class, function (MockInterface $mock) use ($gallery_dto, $uuid) {
        $mock->shouldReceive('getGallery')
            ->once()
            ->with($uuid)
            ->andReturn($gallery_dto);

        $mock->shouldReceive('updateGallery')
            ->once()
            ->withArgs([$uuid, '', 'Updated Description'])
            ->andReturn([
                'success' => true,
                'errors' => [],
                'message' => 'Gallery updated successfully',
            ]);
    });

    $component = Livewire::test(GalleryEdit::class, ['id' => $uuid])
        ->set('name', '')
        ->set('description', 'Updated Description')
        ->call('updateGallery');

    $component->assertRedirect(route('galleries.list'));
    $component->assertSessionHas('message', 'Gallery updated successfully!');
});
