<?php

namespace Tests\Feature;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UploadImageTest extends TestCase
{
    /** @test */
    public function can_upload_image () {
        $this->withoutExceptionHandling();

        Storage::fake('photo');

        $uploadedPhoto = UploadedFile::fake()->image('avatar.jpg');

        $response = $this->post('/image-upload', ['photo' => $uploadedPhoto]);
        // dd($uploadedPhoto->hashName());
        Storage::disk('public')->assertExists('photo/'.$uploadedPhoto->hashName());
    }
}

//! commmand untuk link storage => php artisan storage:link