<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;

class SettingsTest extends FeatureTestBase
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

    public function testAdminCanSeeSettings()
    {
        Permission::create(['name' => 'see_settings', 'title' => 'X']);
        $this->admin->givePermissionTo('see_settings');

        $response = $this->get(route('settings.index'));
        $response->assertOk();
    }

    public function testAdminCannotSeeSettingsWithoutPermission()
    {
        $response = $this->get(route('settings.index'), ['HTTP_REFERER' => route('profile.index')]);
        $response->assertRedirect(route('profile.index'));
    }

    public function testAdminCanUpdateSettings()
    {
        Permission::create(['name' => 'update_settings', 'title' => 'X']);
        $this->admin->givePermissionTo('update_settings');

        $response = $this->sendUpdateRequest();
        $response->assertJson(['status' => 1]);
    }

    public function testAdminCannotUpdateSettingsWithoutPermission()
    {
        $response = $this->sendUpdateRequest();
        $response->assertJson(['status' => 0]);
    }

    private function sendUpdateRequest()
    {
        return $this->json('PUT', route('settings.update'), [
            'tr' => [
                'title' => 'Site Baslik',
            ],
            'en' => [
                'title' => 'Site Title'
            ]
        ]);
    }
}
