<?php

namespace Tests\Feature\Admin;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;
use Tests\Feature\FeatureTestBase;

class UsersTest extends FeatureTestBase
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

    public function test_admin_can_see_users()
    {
        Permission::create(['name' => 'see_users', 'title' => 'X']);
        $this->admin->givePermissionTo('see_users');

        $response = $this->get(route('users.index'));
        $response->assertOk();
    }

    public function test_admin_cannot_see_users_without_permission()
    {
        $response = $this->get(route('users.index'), ['HTTP_REFERER' => route('profile.index')]);
        $response->assertRedirect(route('profile.index'));
    }

    public function test_admin_can_create_a_user()
    {
        Permission::create(['name' => 'create_users', 'title' => 'X']);
        $this->admin->givePermissionTo('create_users');

        $response = $this->json('POST', route('users.create'), [
            'name' => 'Codethereal',
            'email' => 'i@codethereal.com'
        ]);
        $response->assertJson(['status' => 1]);
    }

    public function test_admin_cannot_create_a_user_without_permission()
    {
        $response = $this->json('POST', route('users.create'), [
            'name' => 'Codethereal',
            'email' => 'i@codethereal.com'
        ]);
        $response->assertJson([
            'status' => 0,
        ]);
    }

    public function test_admin_can_see_a_user()
    {
        Permission::create(['name' => 'see_users', 'title' => 'X']);
        $this->admin->givePermissionTo('see_users');

        $response = $this->json('GET', route('users.find', ['id' => $this->admin->id]));
        $response->assertJson(['email' => $this->admin->email]);
    }

    public function test_admin_cannot_see_a_user_without_permission()
    {
        $response = $this->json('GET', route('users.find', ['id' => $this->admin->id]));
        $response->assertJson(['status' => 0]);
    }

    public function test_admin_can_update_a_user()
    {
        Permission::create(['name' => 'update_users', 'title' => 'X']);
        $this->admin->givePermissionTo('update_users');

        $response = $this->json('PUT', route('users.update', ['id' => $this->admin->id]), [
            'name' => 'Codethereal',
            'email' => 'i@codethereal.com'
        ]);
        $response->assertJson(['status' => 1]);
    }

    public function test_admin_cannot_update_a_user_without_permission()
    {
        $response = $this->json('PUT', route('users.update', ['id' => $this->admin->id]), [
            'name' => 'Codethereal',
            'email' => 'i@codethereal.com'
        ]);
        $response->assertJson(['status' => 0]);
    }

    public function test_admin_can_delete_a_user()
    {
        Permission::create(['name' => 'delete_users', 'title' => 'X']);
        $this->admin->givePermissionTo('delete_users');

        $response = $this->json('DELETE', route('users.delete', ['id' => $this->admin->id]));
        $response->assertJson(['status' => 1]);
    }

    public function test_admin_cannot_delete_a_user_without_permission()
    {
        $response = $this->json('DELETE', route('users.delete', ['id' => $this->admin->id]));
        $response->assertJson(['status' => 0]);
    }

    public function test_admin_can_restore_a_user()
    {
        Permission::create(['name' => 'delete_users', 'title' => 'X']);
        $this->admin->givePermissionTo('delete_users');

        $response = $this->json('GET', route('users.restore', ['id' => $this->admin->id]));
        $response->assertJson(['status' => 1]);
    }

    public function test_admin_cannot_restore_a_user_without_permission()
    {
        $response = $this->json('GET', route('users.restore', ['id' => $this->admin->id]));
        $response->assertJson(['status' => 0]);
    }

    public function test_admin_cannot_see_a_developer()
    {
        Permission::create(['name' => 'see_users', 'title' => 'X']);
        $this->admin->givePermissionTo('see_users');

        $response = $this->json('GET', route('users.find', ['id' => $this->dev->id]));
        $response->assertJson(['status' => 0]);
    }

    public function test_admin_cannot_update_a_developer()
    {
        Permission::create(['name' => 'update_users', 'title' => 'X']);
        $this->admin->givePermissionTo('update_users');

        $response = $this->json('PUT', route('users.update', ['id' => $this->dev->id]), ['email' => 'i@codethereal.com']);
        $response->assertJson(['status' => 0]);
    }

    public function test_admin_cannot_delete_a_developer()
    {
        Permission::create(['name' => 'delete_users', 'title' => 'X']);
        $this->admin->givePermissionTo('delete_users');

        $response = $this->json('DELETE', route('users.delete', ['id' => $this->dev->id]));
        $response->assertJson(['status' => 0]);
    }

    public function test_admin_cannot_restore_a_developer()
    {
        Permission::create(['name' => 'delete_users', 'title' => 'X']);
        $this->admin->givePermissionTo('delete_users');

        $response = $this->json('GET', route('users.restore', ['id' => $this->dev->id]));
        $response->assertJson(['status' => 0]);
    }
}
