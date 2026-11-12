<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ApiTokenExpiredTest extends TestCase
{
    use RefreshDatabase;

    protected function tearDown(): void
    {
        Carbon::setTestNow();
        parent::tearDown();
    }

    /**
     * Uji token yang kedaluwarsa mengembalikan 401 Unauthorized.
     *
     * Catatan: Sanctum::actingAs() mem-bypass lookup database dan mengeksekusi mock user in-memory
     * tanpa memeriksa kolom expires_at. Oleh karena itu, pengujian kedaluwarsa token Sanctum yang sesungguhnya
     * memerlukan penerbitan token nyata via createToken(..., expiresAt) dan pengiriman header Authorization Bearer,
     * lalu memajukan waktu melampaui masa berlaku menggunakan Carbon::setTestNow().
     */
    public function test_expired_token_returns_unauthorized_401(): void
    {
        Carbon::setTestNow(now());

        $user = User::factory()->create();

        // Menerbitkan token Sanctum dengan masa berlaku 1 hari (sesuai langkah otorisasi)
        $token = $user->createToken(
            'mobile-kasir',
            ['products:read', 'transactions:create', 'transactions:read'],
            now()->addDay()
        )->plainTextToken;

        // Memajukan waktu melebihi masa berlaku token (2 hari kemudian)
        Carbon::setTestNow(now()->addDays(2));

        // Mengirimkan request dengan token yang sudah kedaluwarsa
        $response = $this->withToken($token)->getJson('/api/user');

        // Memastikan request ditolak dengan 401 Unauthorized karena token kedaluwarsa
        $response->assertUnauthorized();
    }

    /**
     * Memastikan token yang masih aktif (belum kedaluwarsa) dapat mengakses endpoint secara normal (200 OK).
     */
    public function test_valid_token_before_expiration_returns_ok_200(): void
    {
        Carbon::setTestNow(now());

        $user = User::factory()->create();

        $token = $user->createToken(
            'mobile-kasir',
            ['*'],
            now()->addDay()
        )->plainTextToken;

        // Majukan waktu 12 jam (masih dalam jendela berlaku 24 jam)
        Carbon::setTestNow(now()->addHours(12));

        $response = $this->withToken($token)->getJson('/api/user');

        $response->assertOk();
    }
}
