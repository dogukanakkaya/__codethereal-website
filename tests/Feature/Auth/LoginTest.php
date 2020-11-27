<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\Feature\FeatureTestBase;

class LoginTest extends FeatureTestBase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
    }

    public function test_login_form_displays()
    {
        $response = $this->get(route('login'));
        $response->assertStatus(200);
        $response->assertViewIs('auth.login');
    }

    public function test_redirect_to_admin_if_logged_in()
    {
        $response = $this->actingAs($this->admin)->get(route('login'));
        $response->assertRedirect(route('admin.home'));
    }

    public function test_user_can_login_with_correct_credentials()
    {
        $password = '12345678';
        $user = User::factory()->create([
            'name' => 'Login Codethereal',
            'rank' => config('user.rank.admin'),
            'password' => Hash::make($password)
        ]);
        $response = $this->post(route('login'), [
            'email' => $user->email,
            'password' => $password,
        ]);
        $response->assertRedirect(route('admin.home'));
        $this->assertAuthenticatedAs($user);
    }

    public function test_user_cannot_login_with_incorrect_credentials()
    {
        $user = User::factory()->create([
            'name' => 'Login Incorrect',
            'rank' => config('user.rank.admin')
        ]);
        $response = $this->from(route('login'))->post(route('login'), [
            'email' => $user->email,
            'password' => 'invalid-password',
        ]);

        $response->assertRedirect(route('login'));
        $response->assertSessionHasErrors('email');
        $this->assertTrue(session()->hasOldInput('email'));
        $this->assertFalse(session()->hasOldInput('password'));
        $this->assertGuest();
    }
}
