<?php

namespace Tests\Feature;

use Database\Seeders\CriteriaSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SkinRecommendationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(CriteriaSeeder::class);
    }

    public function test_welcome_page_renders_promethee_skin_form(): void
    {
        $response = $this->get('/');

        $response->assertOk();
        $response->assertSee('SkinDecide');
        $response->assertSee('Promethee', false);
        $response->assertSee('data-criterias=', false);
        $response->assertDontSee('onsubmit="prosesHitung(event)"', false);
        $response->assertDontSee('onclick="tambahBarisSkin()"', false);
    }

    public function test_recommendation_api_returns_ranked_skin_results(): void
    {
        $response = $this->postJson('/api/hitung-rekomendasi', [
            'alternatives' => [
                [
                    'name' => 'Skin Mahal Standar',
                    'scores' => [1 => 5000, 2 => 2, 3 => 3, 4 => 3, 5 => 3, 6 => 3, 7 => 3, 8 => 1],
                ],
                [
                    'name' => 'Skin Murah Epic',
                    'scores' => [1 => 1000, 2 => 5, 3 => 6, 4 => 6, 5 => 6, 6 => 7, 7 => 7, 8 => 2],
                ],
            ],
        ]);

        $response->assertOk()
            ->assertJsonPath('status', 'success')
            ->assertJsonPath('rekomendasi.0.name', 'Skin Murah Epic')
            ->assertJsonPath('rekomendasi.0.rank', 1)
            ->assertJsonStructure([
                'status',
                'rekomendasi' => [
                    '*' => ['name', 'code', 'leaving_flow', 'entering_flow', 'net_flow', 'rank'],
                ],
            ]);
    }

    public function test_recommendation_api_requires_two_skins(): void
    {
        $response = $this->postJson('/api/hitung-rekomendasi', [
            'alternatives' => [
                [
                    'name' => 'Skin Tunggal',
                    'scores' => [1 => 1000, 2 => 5, 3 => 6, 4 => 6, 5 => 6, 6 => 7, 7 => 7, 8 => 2],
                ],
            ],
        ]);

        $response->assertUnprocessable()
            ->assertJsonPath('status', 'error');
    }
}
