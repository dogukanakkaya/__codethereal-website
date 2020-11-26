<?php

namespace Tests\Feature\Admin;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;
use Tests\Feature\FeatureTestBase;

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

    public function test_admin_can_see_settings()
    {
        Permission::create(['name' => 'see_settings', 'title' => 'X']);
        $this->admin->givePermissionTo('see_settings');

        $response = $this->get(route('settings.index'));
        $response->assertOk();
    }

    public function test_admin_cannot_see_settings_without_permission()
    {
        $response = $this->get(route('settings.index'), ['HTTP_REFERER' => route('profile.index')]);
        $response->assertRedirect(route('profile.index'));
    }

    public function test_admin_can_update_settings()
    {
        Permission::create(['name' => 'update_settings', 'title' => 'X']);
        $this->admin->givePermissionTo('update_settings');

        $response = $this->json('PUT', route('settings.update'), [
            'tr' => [
                'title' => 'Site Baslik',
            ],
            'en' => [
                'title' => 'Site Title'
            ]
        ]);
        $response->assertJson(['status' => 1]);
    }

    public function test_admin_cannot_update_settings_without_permission()
    {
        $response = $this->json('PUT', route('settings.update'), [
            'tr' => [
                'title' => 'Site Baslik',
            ],
            'en' => [
                'title' => 'Site Title'
            ]
        ]);
        $response->assertJson(['status' => 0]);
    }
}
