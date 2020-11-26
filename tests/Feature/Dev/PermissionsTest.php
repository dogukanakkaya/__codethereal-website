<?php

namespace Tests\Feature\Dev;

use Illuminate\Foundation\Testing\RefreshDatabase;
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

    public function test_developer_can_see_permissions()
    {
        $response = $this->get(route('permissions.index'));
        $response->assertOk();
    }

    public function test_developer_can_create_permissions()
    {
        $response = $this->json('POST', route('permissions.create'), [
            'name' => 'delete_everything',
            'title' => 'DELETE ALL',
            'group ' => 'Group'
        ]);
        $response->assertJson(['status' => 1]);
    }

    public function test_developer_can_see_a_permission()
    {
        $this->json('POST', route('permissions.create'), [
            'name' => 'delete_everything',
            'title' => 'DELETE ALL',
            'group ' => 'Group'
        ]);
        $response = $this->json('GET', route('permissions.find', ['id' => 1]));
        $response->assertJson(['name' => 'delete_everything']);
    }

    public function test_developer_can_update_a_permission()
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

    public function test_developer_can_delete_a_permission()
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
