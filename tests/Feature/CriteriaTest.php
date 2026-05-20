<?php

namespace Tests\Feature;

use App\Models\Criteria;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CriteriaTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_view_criteria_index_page(): void
    {
        $criteria = Criteria::create([
            'name' => 'Kriteria Tes',
            'type' => 'maximize',
            'weight' => 1.5,
            'preference_function' => 'linear',
            'p' => 5,
        ]);

        $response = $this->get('/kriteria');

        $response->assertStatus(200);
        $response->assertSee('Kriteria Tes');
        $response->assertSee('1.5');
        $response->assertDontSee('onchange="toggleParams(this', false);
        $response->assertDontSee('onclick="confirmDelete(', false);
        $response->assertDontSee('style="display:none;"', false);
    }

    public function test_user_can_create_new_criteria(): void
    {
        $response = $this->post('/kriteria', [
            'name' => 'Kriteria Baru',
            'type' => 'maximize',
            'weight' => 2.0,
            'preference_function' => 'linear',
            'p' => 10,
        ]);

        $response->assertRedirect('/kriteria');
        $this->assertDatabaseHas('criterias', [
            'name' => 'Kriteria Baru',
            'type' => 'maximize',
            'weight' => 2.0,
            'preference_function' => 'linear',
            'p' => 10,
        ]);
    }

    public function test_user_can_update_existing_criteria(): void
    {
        $criteria = Criteria::create([
            'name' => 'Kriteria Awal',
            'type' => 'maximize',
            'weight' => 1.0,
            'preference_function' => 'usual',
        ]);

        $response = $this->put("/kriteria/{$criteria->id}", [
            'name' => 'Kriteria Diubah',
            'type' => 'minimize',
            'weight' => 1.8,
            'preference_function' => 'linear_quasi',
            'p' => 8,
            'q' => 2,
        ]);

        $response->assertRedirect('/kriteria');
        $this->assertDatabaseHas('criterias', [
            'id' => $criteria->id,
            'name' => 'Kriteria Diubah',
            'type' => 'minimize',
            'weight' => 1.8,
            'preference_function' => 'linear_quasi',
            'p' => 8,
            'q' => 2,
        ]);
    }

    public function test_user_can_delete_criteria(): void
    {
        $criteria = Criteria::create([
            'name' => 'Kriteria Hapus',
            'type' => 'maximize',
            'weight' => 1.0,
            'preference_function' => 'usual',
        ]);

        $response = $this->delete("/kriteria/{$criteria->id}");

        $response->assertRedirect('/kriteria');
        $this->assertDatabaseMissing('criterias', [
            'id' => $criteria->id,
        ]);
    }
}
