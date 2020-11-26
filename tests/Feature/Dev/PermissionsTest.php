<?php

namespace Tests\Feature\Dev;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;
use Tests\Feature\FeatureTestBase;

class PermissionsTest extends FeatureTestBase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        $this->actingAs($this->dev)
            ->withHeaders([
                'X-Requested-With' => 'XMLHttpRequest',
            ]);
    }

    public function testDevCanSeePermissions()
    {
        $response = $this->get(route('permissions.index'));
        $response->assertOk();
    }

    public function testDevCanCreateAPermission()
    {
        $response = $this->json('POST', route('permissions.create'), [
            'name' => 'delete_everything',
            'title' => 'DELETE ALL',
            'group ' => 'Group'
        ]);
        $response->assertJson(['status' => 1]);
    }

    public function testDevCanSeeAPermission()
    {
        $this->json('POST', route('permissions.create'), [
            'name' => 'delete_everything',
            'title' => 'DELETE ALL',
            'group ' => 'Group'
        ]);
        $response = $this->json('GET', route('permissions.find', ['id' => 1]));
        $response->assertJson(['name' => 'delete_everything']);
    }

    public function testDevCanUpdateAPermission()
    {
        $this->json('POST', route('permissions.create'), [
            'name' => 'delete_everything',
            'title' => 'DELETE ALL',
            'group ' => 'Group'
        ]);
        $response = $this->json('PUT', route('permissions.update', ['id' => 1]), [
            'name' => 'update_everything',
            'title' => 'DELETE ALL',
        ]);
        $response->assertJson(['status' => 1]);
    }

    public function testDevCanDeleteAPermission()
    {
        $this->json('POST', route('permissions.create'), [
            'name' => 'delete_everything',
            'title' => 'DELETE ALL',
            'group ' => 'Group'
        ]);
        $response = $this->json('DELETE', route('permissions.delete', ['id' => 1]));
        $response->assertJson(['status' => 1]);
    }
}
