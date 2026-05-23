<?php

namespace Tests\Feature;

use App\Models\Criteria;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PengaturanTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('public_images');

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

    public function test_admin_can_view_custom_background_page(): void
    {
        $response = $this->actingAs($this->admin)->get('/custom-background');

        $response->assertStatus(200);
        $response->assertSee('Custom <span>Background</span>', false);
        $response->assertSee('/custom-background/upload', false);
        $response->assertSee('/custom-background/reset', false);
        $response->assertDontSee('http://localhost/custom-background', false);
    }

    public function test_admin_custom_background_upload_is_applied_to_public_visitors(): void
    {
        $response = $this->actingAs($this->admin)->post('/custom-background/upload', [
            'background' => UploadedFile::fake()->image('background.png', 1200, 800)->size(512),
        ]);

        $response->assertRedirect('/custom-background');
        Storage::disk('public_images')->assertExists('site-background.png');

        $publicResponse = $this->get('/');

        $publicResponse->assertStatus(200);
        $publicResponse->assertSee('/images/site-background.png', false);
        $publicResponse->assertSee('data-custom-background-url=', false);
    }

    public function test_legacy_custom_background_upload_endpoint_is_still_accepted(): void
    {
        $response = $this->actingAs($this->admin)->post('/custom-background', [
            'background' => UploadedFile::fake()->image('background.png', 1200, 800)->size(512),
        ]);

        $response->assertRedirect('/custom-background');
        Storage::disk('public_images')->assertExists('site-background.png');
    }

    public function test_admin_can_reset_custom_background_to_default(): void
    {
        $this->actingAs($this->admin)->post('/custom-background/upload', [
            'background' => UploadedFile::fake()->image('background.png', 1200, 800)->size(512),
        ]);

        $response = $this->actingAs($this->admin)->post('/custom-background/reset');

        $response->assertRedirect('/custom-background');
        Storage::disk('public_images')->assertMissing('site-background.png');

        $this->get('/')->assertDontSee('/images/site-background.png', false);
    }
}
