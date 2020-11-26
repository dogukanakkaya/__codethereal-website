<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;

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

    public function testAdminCanSeeUsers()
    {
        Permission::create(['name' => 'see_users', 'title' => 'X']);
        $this->admin->givePermissionTo('see_users');

        $response = $this->get(route('users.index'));
        $response->assertOk();
    }

    public function testAdminCannotSeeUsersWithoutPermission()
    {
        $response = $this->get(route('users.index'), ['HTTP_REFERER' => route('profile.index')]);
        $response->assertRedirect(route('profile.index'));
    }

    public function testAdminCanCreateAUser()
    {
        Permission::create(['name' => 'create_users', 'title' => 'X']);
        $this->admin->givePermissionTo('create_users');

        $response = $this->json('POST', route('users.create'), [
            'name' => 'Codethereal',
            'email' => 'i@codethereal.com'
        ]);
        $response->assertJson(['status' => 1]);
    }

    public function testAdminCannotCreateAUserWithoutPermission()
    {
        $response = $this->json('POST', route('users.create'), [
            'name' => 'Codethereal',
            'email' => 'i@codethereal.com'
        ]);
        $response->assertJson([
            'status' => 0,
        ]);
    }

    public function testAdminCanSeeAUser()
    {
        Permission::create(['name' => 'see_users', 'title' => 'X']);
        $this->admin->givePermissionTo('see_users');

        $response = $this->json('GET', route('users.find', ['id' => $this->admin->id]));
        $response->assertJson(['email' => $this->admin->email]);
    }

    public function testAdminCannotSeeAUserWithoutPermission()
    {
        $response = $this->json('GET', route('users.find', ['id' => $this->admin->id]));
        $response->assertJson(['status' => 0]);
    }

    public function testAdminCanUpdateAUser()
    {
        Permission::create(['name' => 'update_users', 'title' => 'X']);
        $this->admin->givePermissionTo('update_users');

        $response = $this->json('PUT', route('users.update', ['id' => $this->admin->id]), [
            'name' => 'Codethereal',
            'email' => 'i@codethereal.com'
        ]);
        $response->assertJson(['status' => 1]);
    }

    public function testAdminCannotUpdateAUserWithoutPermission()
    {
        $response = $this->json('PUT', route('users.update', ['id' => $this->admin->id]), [
            'name' => 'Codethereal',
            'email' => 'i@codethereal.com'
        ]);
        $response->assertJson(['status' => 0]);
    }

    public function testAdminCanDeleteAUser()
    {
        Permission::create(['name' => 'delete_users', 'title' => 'X']);
        $this->admin->givePermissionTo('delete_users');

        $response = $this->json('DELETE', route('users.delete', ['id' => $this->admin->id]));
        $response->assertJson(['status' => 1]);
    }

    public function testAdminCannotDeleteAUserWithoutPermission()
    {
        $response = $this->json('DELETE', route('users.delete', ['id' => $this->admin->id]));
        $response->assertJson(['status' => 0]);
    }

    public function testAdminCanRestoreAUser()
    {
        Permission::create(['name' => 'delete_users', 'title' => 'X']);
        $this->admin->givePermissionTo('delete_users');

        $response = $this->json('GET', route('users.restore', ['id' => $this->admin->id]));
        $response->assertJson(['status' => 1]);
    }

    public function testAdminCannotRestoreAUserWithoutPermission()
    {
        $response = $this->json('GET', route('users.restore', ['id' => $this->admin->id]));
        $response->assertJson(['status' => 0]);
    }

    public function testAdminCannotOperateADeveloper()
    {
        Permission::create(['name' => 'see_users', 'title' => 'X']);
        Permission::create(['name' => 'update_users', 'title' => 'X']);
        Permission::create(['name' => 'delete_users', 'title' => 'X']);
        $this->admin->givePermissionTo(['see_users', 'update_users', 'delete_users']);

        // Admin can't find developer
        $response = $this->json('GET', route('users.find', ['id' => $this->dev->id]));
        $response->assertJson(['status' => 0]);

        // Admin can't update developer
        $response = $this->json('PUT', route('users.update', ['id' => $this->dev->id]), ['email' => 'i@codethereal.com']);
        $response->assertJson(['status' => 0]);

        // Admin can't delete developer
        $response = $this->json('DELETE', route('users.delete', ['id' => $this->dev->id]));
        $response->assertJson(['status' => 0]);

        // Admin can't restore developer
        $response = $this->json('GET', route('users.restore', ['id' => $this->dev->id]));
        $response->assertJson(['status' => 0]);
    }
}
