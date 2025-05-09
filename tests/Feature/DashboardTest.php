<?php

use App\Models\User;

test('guests are redirected to the login page', function () {
    $this->get('/dashboard')->assertRedirect('/login');
});

test('authenticated users are redirected to galleries list page', function () {
    $this->actingAs($user = User::factory()->create());

    $this->get('/dashboard')->assertStatus(302)
        ->assertRedirect('/galleries');
});
