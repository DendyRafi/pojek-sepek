<?php

namespace Tests\Feature;

use App\Models\Criteria;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PengaturanTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->admin()->create();
    }

    public function test_admin_can_view_criteria_index_page(): void
    {
        Criteria::create([
            'name' => 'Kriteria Tes',
            'type' => 'maximize',
            'weight' => 1.5,
            'preference_function' => 'linear',
            'p' => 5,
        ]);

        $response = $this->actingAs($this->admin)->get('/pengaturan');

        $response->assertStatus(200);
        $response->assertSee('Kriteria Tes');
        $response->assertSee('1.5');
        $response->assertSee('Reset Kriteria Semula');
        $response->assertSee('data-confirm-reset', false);
        $response->assertDontSee('onchange="toggleParams(this', false);
        $response->assertDontSee('onclick="confirmDelete(', false);
        $response->assertDontSee('window.confirm', false);
        $response->assertDontSee('style="display:none;"', false);
    }

    public function test_admin_can_create_new_criteria(): void
    {
        $response = $this->actingAs($this->admin)->post('/pengaturan', [
            'name' => 'Kriteria Baru',
            'type' => 'maximize',
            'weight' => 2.0,
            'preference_function' => 'linear',
            'p' => 10,
        ]);

        $response->assertRedirect('/pengaturan');
        $this->assertDatabaseHas('criterias', [
            'name' => 'Kriteria Baru',
            'type' => 'maximize',
            'weight' => 2.0,
            'preference_function' => 'linear',
            'p' => 10,
        ]);
    }

    public function test_admin_can_update_existing_criteria(): void
    {
        $criteria = Criteria::create([
            'name' => 'Kriteria Awal',
            'type' => 'maximize',
            'weight' => 1.0,
            'preference_function' => 'usual',
        ]);

        $response = $this->actingAs($this->admin)->put("/pengaturan/{$criteria->id}", [
            'name' => 'Kriteria Diubah',
            'type' => 'minimize',
            'weight' => 1.8,
            'preference_function' => 'linear_quasi',
            'p' => 8,
            'q' => 2,
        ]);

        $response->assertRedirect('/pengaturan');
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

    public function test_admin_can_delete_criteria(): void
    {
        $criteria = Criteria::create([
            'name' => 'Kriteria Hapus',
            'type' => 'maximize',
            'weight' => 1.0,
            'preference_function' => 'usual',
        ]);

        $response = $this->actingAs($this->admin)->delete("/pengaturan/{$criteria->id}");

        $response->assertRedirect('/pengaturan');
        $this->assertDatabaseMissing('criterias', [
            'id' => $criteria->id,
        ]);
    }
}
