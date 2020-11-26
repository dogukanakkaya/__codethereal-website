<?php

namespace Tests\Feature\Admin;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Feature\FeatureTestBase;

class ProfileTest extends FeatureTestBase
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

    public function test_user_can_see_profile()
    {
        $this->withoutExceptionHandling();
        $response = $this->get(route('profile.index'));
        $response->assertOk();
    }

    public function test_user_can_update_profile()
    {
        $response = $this->json('PUT', route('profile.update'), [
            'name' => 'Codethereal',
        ]);
        $response->assertJson(['status' => 1]);
    }
}
