<?php

namespace Tests\Feature\Admin;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
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

    public function test_profile_form_displays()
    {
        $response = $this->get(route('profile.index'));
        $response->assertOk();
    }

    public function test_user_can_update_self_profile()
    {
        $response = $this->json('PUT', route('profile.update'), [
            'name' => 'Codethereal',
        ]);
        $response->assertJson(['status' => 1]);
    }

    public function test_profile_name_field_is_required()
    {
        $response = $this->json('PUT', route('profile.update'), [
            'name' => '',
        ]);
        $response->assertJson(['status' => 0]);
    }

    public function test_profile_name_field_must_be_max_255_character_length()
    {
        $response = $this->json('PUT', route('profile.update'), [
            'name' => Str::random(256),
        ]);
        $response->assertJson(['status' => 0]);
    }

    public function test_profile_position_field_must_be_max_255_character_length()
    {
        $response = $this->json('PUT', route('profile.update'), [
            'name' => 'Codethereal',
            'position' => Str::random(256),
        ]);
        $response->assertJson(['status' => 0]);
    }

    public function test_profile_image_field_must_be_integer()
    {
        $response = $this->json('PUT', route('profile.update'), [
            'name' => 'Codethereal',
            'image' => 'string_value'
        ]);
        $response->assertJson(['status' => 0]);
    }

    public function test_profile_about_field_must_be_string()
    {
        $response = $this->json('PUT', route('profile.update'), [
            'name' => 'Codethereal',
            'about' => 1
        ]);
        $response->assertJson(['status' => 0]);
    }
}
