<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use Spatie\Permission\Models\Role;
use Tests\TestCase;


class FeatureTestBase extends TestCase
{
    /**
     * ! Laravel advices us to use camelcase in tests, but a lot of people would agree snake case is more readable
     */

    use RefreshDatabase;

    protected User $admin;
    protected User $dev;

    public function setUp(): void
    {
        parent::setUp();

        $this->app->make(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();

        $this->withHeaders([
            "HTTP_USER_AGENT" => "Mozilla/5.0 (Macintosh; Intel Mac OS X 11_0_0) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/86.0.4240.198 Safari/537.36",
        ]);

        Role::create([
            'name' => 'developer'
        ]);

        config()->set('laravellocalization.hideDefaultLocaleInURL', true);

        $this->admin = User::factory()->create([
            'name' => 'Admin',
            'rank' => config('user.rank.admin'),
            'image' => 0
        ]);
        $this->admin->markEmailAsVerified();
        $this->admin->markAsAuthorized();

        $this->dev = User::factory()->create([
            'name' => 'Developer',
            'rank' => config('user.rank.dev'),
            'image' => 0
        ]);
        $this->dev->assignRole('developer');
        $this->dev->markEmailAsVerified();
        $this->dev->markAsAuthorized();
    }
}
