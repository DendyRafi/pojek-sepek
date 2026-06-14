<?php

namespace Tests\Feature;

use App\Models\Criteria;
use App\Models\User;
use App\Support\DefaultCriteria;
use Database\Seeders\AdminUserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminCriteriaManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_to_admin_login_before_managing_criteria(): void
    {
        $this->get('/pengaturan')
            ->assertRedirect('/login');
    }

    public function test_default_admin_can_login_and_access_criteria_settings(): void
    {
        $this->seed(AdminUserSeeder::class);

        $response = $this->post('/login', [
            'username' => AdminUserSeeder::DEFAULT_USERNAME,
            'password' => AdminUserSeeder::DEFAULT_PASSWORD,
        ]);

        $response->assertRedirect('/pengaturan');
        $response->assertSessionHasNoErrors();
        $this->assertAuthenticated();

        $this->get('/pengaturan')
            ->assertOk()
            ->assertSee('Pengaturan')
            ->assertSee('Reset Password')
            ->assertSee('Reset Kriteria Semula');
    }

    public function test_non_admin_user_cannot_login_to_admin_session(): void
    {
        User::factory()->create([
            'username' => 'user@example.test',
            'password' => Hash::make('password'),
            'is_admin' => false,
        ]);

        $this->post('/login', [
            'username' => 'user@example.test',
            'password' => 'password',
        ])->assertSessionHasErrors('username');

        $this->assertGuest();
    }

    public function test_admin_can_reset_criteria_to_default_records(): void
    {
        $admin = User::factory()->admin()->create();
        Criteria::create([
            'name' => 'Kriteria Rusak',
            'type' => 'maximize',
            'weight' => 9,
            'preference_function' => 'usual',
        ]);

        $this->actingAs($admin)
            ->post('/pengaturan/reset')
            ->assertRedirect('/pengaturan')
            ->assertSessionHas('success');

        $this->assertSame(count(DefaultCriteria::records()), Criteria::count());
        $this->assertSame(
            range(1, count(DefaultCriteria::records())),
            Criteria::query()->orderBy('id')->pluck('id')->all(),
        );
        $this->assertDatabaseHas('criterias', [
            'name' => 'Harga (Diamond)',
            'type' => 'minimize',
            'weight' => 1.5,
            'preference_function' => 'linear',
            'p' => 9000,
        ]);
        $this->assertDatabaseMissing('criterias', [
            'name' => 'Kriteria Rusak',
        ]);
    }

    public function test_admin_can_update_password_and_login_with_new_password(): void
    {
        $admin = User::factory()->admin()->create([
            'username' => 'admin@example.test',
            'password' => Hash::make('OldPassword123'),
        ]);

        $this->actingAs($admin)
            ->put('/admin/password', [
                'current_password' => 'OldPassword123',
                'password' => 'NewPassword123',
                'password_confirmation' => 'NewPassword123',
            ])
            ->assertRedirect('/admin/password')
            ->assertSessionHas('success');

        $this->post('/logout');

        $this->post('/login', [
            'username' => 'admin@example.test',
            'password' => 'NewPassword123',
        ])->assertRedirect('/pengaturan');

        $this->assertAuthenticatedAs($admin->fresh());
    }
}
