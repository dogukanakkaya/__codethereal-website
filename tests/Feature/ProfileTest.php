<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;

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

    public function testUserCanSeeProfile()
    {
        $response = $this->get(route('profile.index'));
        $response->assertOk();
    }

    public function testUserCanUpdateProfile()
    {
        $response = $this->json('PUT', route('profile.update'), [
            'name' => 'Codethereal',
        ]);
        $response->assertJson(['status' => 1]);
    }
}
