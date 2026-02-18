<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\Hostel;
use App\Models\Payment;
use App\Models\Room;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthAndDashboardAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_student_can_register_and_is_logged_in(): void
    {
        $response = $this->post('/register', [
            'name' => 'Student User',
            'email' => 'student@example.com',
            'phone' => '0200000000',
            'university' => 'KNUST',
            'role' => 'student',
            'student_id' => 'STU-1001',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertRedirect('/hostels');
        $this->assertAuthenticated();

        $this->assertDatabaseHas('users', [
            'email' => 'student@example.com',
            'role' => 'student',
            'is_active' => true,
        ]);
    }

    public function test_manager_registration_creates_inactive_account_and_redirects_to_login(): void
    {
        $response = $this->post('/register', [
            'name' => 'Manager User',
            'email' => 'manager@example.com',
            'phone' => '0240000000',
            'university' => 'UG',
            'role' => 'manager',
            'student_id' => null,
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertRedirect('/login');
        $this->assertGuest();

        $this->assertDatabaseHas('users', [
            'email' => 'manager@example.com',
            'role' => 'manager',
            'is_active' => false,
        ]);
    }

    public function test_inactive_manager_cannot_log_in(): void
    {
        $manager = User::factory()->create([
            'role' => 'manager',
            'is_active' => false,
            'password' => 'password123',
        ]);

        $response = $this->post('/login', [
            'email' => $manager->email,
            'password' => 'password123',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_login_redirects_by_role(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
            'is_active' => true,
            'password' => 'password123',
        ]);

        $manager = User::factory()->create([
            'role' => 'manager',
            'is_active' => true,
            'password' => 'password123',
        ]);

        $student = User::factory()->create([
            'role' => 'student',
            'is_active' => true,
            'password' => 'password123',
            'student_id' => 'STU-2002',
        ]);

        $this->post('/login', [
            'email' => $admin->email,
            'password' => 'password123',
        ])->assertRedirect('/admin/dashboard');

        $this->post('/logout');

        $this->post('/login', [
            'email' => $manager->email,
            'password' => 'password123',
        ])->assertRedirect('/manager/dashboard');

        $this->post('/logout');

        $this->post('/login', [
            'email' => $student->email,
            'password' => 'password123',
        ])->assertRedirect('/hostels');
    }

    public function test_admin_can_approve_manager(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
            'is_active' => true,
        ]);

        $manager = User::factory()->create([
            'role' => 'manager',
            'is_active' => false,
        ]);

        $this->actingAs($admin)
            ->post("/admin/managers/{$manager->id}/approve")
            ->assertRedirect();

        $manager->refresh();

        $this->assertTrue($manager->is_active);
        $this->assertNotNull($manager->approved_at);
    }

    public function test_role_middleware_blocks_unauthorized_dashboard_access(): void
    {
        $student = User::factory()->create([
            'role' => 'student',
            'is_active' => true,
            'student_id' => 'STU-3003',
        ]);

        $this->actingAs($student)
            ->get('/admin/dashboard')
            ->assertForbidden();

        $this->actingAs($student)
            ->get('/manager/dashboard')
            ->assertForbidden();
    }

    public function test_manager_dashboard_loads_with_stats_data(): void
    {
        $manager = User::factory()->create([
            'role' => 'manager',
            'is_active' => true,
        ]);

        $student = User::factory()->create([
            'role' => 'student',
            'is_active' => true,
            'student_id' => 'STU-4004',
        ]);

        $hostel = Hostel::create([
            'manager_id' => $manager->id,
            'name' => 'Unity Hostel',
            'slug' => 'unity-hostel',
            'description' => 'A verified hostel.',
            'gender_policy' => 'mixed',
            'address' => 'Campus Road',
            'distance_from_campus' => '5 mins',
            'university' => 'KNUST',
            'images' => [],
            'is_active' => true,
        ]);

        $room = Room::create([
            'hostel_id' => $hostel->id,
            'type' => '2-in-a-room',
            'price_per_semester' => 1200,
            'total_beds' => 10,
            'available_beds' => 7,
        ]);

        $booking = Booking::create([
            'hostel_id' => $hostel->id,
            'room_id' => $room->id,
            'student_id' => $student->id,
            'status' => 'confirmed',
            'academic_year' => '2026/2027',
            'semester' => 'first',
        ]);

        Payment::create([
            'transaction_id' => 'TXN-1001',
            'booking_id' => $booking->id,
            'student_id' => $student->id,
            'method' => 'mtn_momo',
            'status' => 'success',
            'amount' => 1200,
        ]);

        $this->actingAs($manager)
            ->get('/manager/dashboard')
            ->assertOk();
    }
}
