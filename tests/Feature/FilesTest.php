<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class FilesTest extends FeatureTestBase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        $this->actingAs($this->admin)
            ->withHeaders([
                'X-Requested-With' => 'XMLHttpRequest',
            ]);
    }

    public function testFileCanUpload()
    {
        Storage::fake('public');

        $file = UploadedFile::fake()->image('avatar.jpg');

        $response = $this->json('POST', route('files.upload'), [
            'file' => $file,
        ]);

        // Assert the file was stored...
        Storage::disk('public')->assertExists($file->hashName());

        // Assert a file does not exist...
        Storage::disk('public')->assertMissing('missing.jpg');
    }

    public function testFileCanDelete()
    {
        Storage::fake('public');

        $file = UploadedFile::fake()->image('avatar.jpg');

        $this->json('POST', route('files.upload'), [
            'file' => $file,
        ]);

        $response = $this->json('DELETE', route('files.delete', ['id' => 1]));
        $response->assertJson(['status' => 1]);
    }
}
