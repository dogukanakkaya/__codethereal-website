<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use Tests\TestCase;

class FeatureTestBase extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected User $dev;

    public function setUp(): void
    {
        parent::setUp();

        $this->app->make(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();

        $this->admin = User::factory()->create([
            'name' => 'Admin',
            'rank' => Config::get('constants.rank.admin'),
            'image' => 0,
        ]);

        $this->dev = User::factory()->create([
            'name' => 'Developer',
            'rank' => Config::get('constants.rank.dev'),
            'image' => 0,
        ]);
    }
}
