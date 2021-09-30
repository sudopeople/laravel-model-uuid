<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Route;
use Tests\Fixtures\CustomUuidRouteBoundPost;
use Tests\Fixtures\MultipleUuidRouteBoundPost;
use Tests\Fixtures\UuidRouteBoundPost;
use Tests\TestCase;

class BindUuidTest extends TestCase
{
    /** @test */
    public function it_binds_to_default_uuid_field()
    {
        $post = factory(UuidRouteBoundPost::class)->create();

        Route::middleware('bindings')->get('/posts/{post}', function (UuidRouteBoundPost $post) {
            return $post;
        })->name('posts.show');

        $this->get('/posts/'.$post->uuid)->assertSuccessful();
        $this->get(route('posts.show', $post))->assertSuccessful();
    }

    /** @test */
    public function it_fails_on_invalid_default_uuid_field_value()
    {
        $post = factory(UuidRouteBoundPost::class)->create();

        Route::middleware('bindings')->get('/posts/{post}', function (UuidRouteBoundPost $post) {
            return $post;
        })->name('posts.show');

        $this->get('/posts/'.$post->custom_uuid)->assertNotFound();
        $this->get(route('posts.show', $post->custom_uuid))->assertNotFound();
    }

    /** @test */
    public function it_binds_to_custom_uuid_field()
    {
        $post = factory(CustomUuidRouteBoundPost::class)->create();

        Route::middleware('bindings')->get('/posts/{post}', function (CustomUuidRouteBoundPost $post) {
            return $post;
        })->name('posts.show');

        $this->get('/posts/'.$post->custom_uuid)->assertSuccessful();
        $this->get(route('posts.show', $post))->assertSuccessful();
    }

    /** @test */
    public function it_fails_on_invalid_custom_uuid_field_value()
    {
        $post = factory(CustomUuidRouteBoundPost::class)->create();

        Route::middleware('bindings')->get('/posts/{post}', function (CustomUuidRouteBoundPost $post) {
            return $post;
        })->name('posts.show');

        $this->get('/posts/'.$post->uuid)->assertNotFound();
        $this->get(route('posts.show', $post->uuid))->assertNotFound();
    }

    /** @test */
    public function it_binds_to_declared_uuid_column_instead_of_default_when_custom_key_used()
    {
        $post = factory(MultipleUuidRouteBoundPost::class)->create();

        Route::middleware('bindings')->get('/posts/{post:custom_uuid}', function (MultipleUuidRouteBoundPost $post) {
            return $post;
        })->name('posts.show');

        $this->get('/posts/'.$post->custom_uuid)->assertSuccessful();
        $this->get(route('posts.show', $post))->assertSuccessful();
    }

    /** @test */
    public function it_fails_on_invalid_uuid_when_custom_route_key_used()
    {
        $post = factory(MultipleUuidRouteBoundPost::class)->create();

        Route::middleware('bindings')->get('/posts/{post:custom_uuid}', function (MultipleUuidRouteBoundPost $post) {
            return $post;
        })->name('posts.show');

        $this->get('/posts/'.$post->uuid)->assertNotFound();
        $this->get(route('posts.show', $post->uuid))->assertNotFound();
    }
}
