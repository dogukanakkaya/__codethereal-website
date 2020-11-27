<?php

namespace Tests\Feature\Admin;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\Feature\FeatureTestBase;

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

    public function test_admin_can_upload_files()
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

    public function test_admin_can_delete_files()
    {
        Storage::fake('public');

        $file = UploadedFile::fake()->image('avatar.jpg');

        $this->json('POST', route('files.upload'), [
            'file' => $file,
        ]);

        $response = $this->json('DELETE', route('files.destroy', ['id' => 1]));
        $response->assertJson(['status' => 1]);
    }
}
