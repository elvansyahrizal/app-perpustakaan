<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    public function test_the_application_returns_a_successful_response(): void
    {
        $response = $this->get('/');

        $response->assertRedirect('/login');
    }

    public function test_login_screen_can_be_rendered(): void
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
        $response->assertSee('Login Perpustakaan');
    }

    public function test_users_can_authenticate_using_the_login_screen(): void
    {
        $user = User::factory()->create([
            'email' => 'admin@pens.ac.id',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        $response = $this->post('/login', [
            'email' => 'admin@pens.ac.id',
            'password' => 'password',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('books.index'));
    }

    public function test_users_can_not_authenticate_with_invalid_password(): void
    {
        $user = User::factory()->create([
            'email' => 'admin@pens.ac.id',
            'password' => Hash::make('password'),
        ]);

        $this->post('/login', [
            'email' => 'admin@pens.ac.id',
            'password' => 'wrong-password',
        ]);

        $this->assertGuest();
    }

    public function test_users_can_logout(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/logout');

        $this->assertGuest();
        $response->assertRedirect(route('login'));
    }

    public function test_admin_can_access_categories(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->get('/categories');

        $response->assertStatus(200);
    }

    public function test_petugas_cannot_access_categories(): void
    {
        $petugas = User::factory()->create(['role' => 'petugas']);

        $response = $this->actingAs($petugas)->get('/categories');

        $response->assertStatus(403);
    }

    public function test_user_seeder_creates_specified_users(): void
    {
        $this->seed(UserSeeder::class);

        $this->assertDatabaseHas('users', [
            'email' => 'admin@pens.ac.id',
            'role' => 'admin',
        ]);
        $this->assertDatabaseHas('users', [
            'email' => 'petugas1@pens.ac.id',
            'role' => 'petugas',
        ]);
        $this->assertDatabaseHas('users', [
            'email' => 'petugas2@pens.ac.id',
            'role' => 'petugas',
        ]);
    }

    public function test_user_can_view_profile(): void
    {
        $user = User::factory()->create([
            'name' => 'Petugas Satu',
            'email' => 'petugas1@pens.ac.id',
            'role' => 'petugas',
        ]);

        $response = $this->actingAs($user)->get('/profil');

        $response->assertStatus(200);
        $response->assertSee('Petugas Satu');
        $response->assertSee('petugas1@pens.ac.id');
    }

    public function test_user_can_change_password(): void
    {
        $user = User::factory()->create([
            'password' => Hash::make('oldpassword123'),
        ]);

        $response = $this->actingAs($user)->put('/profil/password', [
            'password_lama' => 'oldpassword123',
            'password_baru' => 'newpassword123',
            'password_baru_confirmation' => 'newpassword123',
        ]);

        $response->assertRedirect(route('profile.show'));
        $response->assertSessionHas('success');
        $this->assertTrue(Hash::check('newpassword123', $user->fresh()->password));
    }

    public function test_authenticated_user_can_create_loan(): void
    {
        $user = User::factory()->create(['role' => 'petugas']);
        $category = \App\Models\Category::create([
            'nama_kategori' => 'Teknologi',
            'deskripsi' => 'Buku seputar IT',
        ]);
        $member = \App\Models\Member::create([
            'nama' => 'Budi',
            'nim' => '12345678',
            'email' => 'budi@test.com',
            'nomor_telepon' => '08123456789',
            'alamat' => 'Surabaya',
            'status' => 'aktif',
        ]);
        $book = \App\Models\Book::create([
            'judul' => 'Laravel Guide',
            'penulis' => 'Taylor',
            'penerbit' => 'Laravel',
            'tahun_terbit' => 2024,
            'stok' => 5,
            'category_id' => $category->id,
        ]);

        $response = $this->actingAs($user)->post('/loans', [
            'member_id' => $member->id,
            'tanggal_pinjam' => now()->toDateString(),
            'tanggal_kembali' => now()->addDays(7)->toDateString(),
            'book_ids' => [$book->id],
        ]);

        $response->assertRedirect(route('loans.index'));
        $this->assertDatabaseHas('loans', [
            'member_id' => $member->id,
            'user_id' => $user->id,
        ]);
    }
}
