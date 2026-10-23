<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\DemoUserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ManualScenarioTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DemoUserSeeder::class);
    }

    public function test_guest_can_access_info(): void
    {
        $response = $this->get('/info');
        $response->assertStatus(200);
    }

    public function test_guest_cannot_access_products_and_redirects_to_login(): void
    {
        $response = $this->get('/products');
        $response->assertRedirect('/login');
    }

    public function test_kasir_redirected_to_pos_when_accessing_info(): void
    {
        $kasir = User::where('email', 'kasir@pos.test')->first();
        $response = $this->actingAs($kasir)->get('/info');
        $response->assertRedirect('/pos');
    }

    public function test_kasir_cannot_access_products_and_gets_403_forbidden(): void
    {
        $kasir = User::where('email', 'kasir@pos.test')->first();
        $response = $this->actingAs($kasir)->get('/products');
        $response->assertStatus(403);
    }

    public function test_admin_can_access_products(): void
    {
        $admin = User::where('email', 'admin@pos.test')->first();
        $response = $this->actingAs($admin)->get('/products');
        $response->assertStatus(200);
    }

    public function test_admin_redirected_to_pos_when_accessing_info(): void
    {
        $admin = User::where('email', 'admin@pos.test')->first();
        $response = $this->actingAs($admin)->get('/info');
        $response->assertRedirect('/pos');
    }
}
