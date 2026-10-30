<?php

namespace Tests\Feature;

use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Invoice;
use App\Models\InvoiceChangeLog;
use App\Models\Patient;
use App\Models\Room;
use App\Models\User;
use Database\Seeders\RoomSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->actingAs(User::create([
            'name' => 'admin',
            'email' => 'admin@clinic.local',
            'password' => 'admin',
        ]));
    }

    public function test_the_application_returns_a_successful_response(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function test_dashboard_shows_today_metrics_and_keeps_recent_sections(): void
    {
        $patient = Patient::create([
            'first_name' => 'Today',
            'last_name' => 'Patient',
            'phone' => '09171234567',
        ]);
        $futurePatient = Patient::create([
            'first_name' => 'Future',
            'last_name' => 'Patient',
            'phone' => '09171234568',
        ]);
        $doctor = Doctor::create([
            'first_name' => 'Jordan',
            'last_name' => 'Doctor',
            'specialization' => 'General Practice',
            'phone' => '09181234567',
            'email' => 'jordan.doctor@example.com',
        ]);

        Appointment::create([
            'patient_id' => $patient->id,
            'doctor_id' => $doctor->id,
            'appointment_date' => today()->format('Y-m-d') . ' 09:00:00',
            'status' => 'Completed',
        ]);
        Appointment::create([
            'patient_id' => $futurePatient->id,
            'doctor_id' => $doctor->id,
            'appointment_date' => today()->addDay()->format('Y-m-d') . ' 09:00:00',
            'status' => 'Scheduled',
        ]);
        Invoice::create([
            'patient_id' => $patient->id,
            'amount' => 500,
            'status' => 'Unpaid',
            'due_date' => today()->addWeek()->format('Y-m-d'),
        ]);

        $this->get(route('dashboard'))
            ->assertOk()
            ->assertSee('Visits today')
            ->assertSee('Completed today')
            ->assertSee('Unpaid invoices')
            ->assertSee('Recent Appointments')
            ->assertSee('Pending Unpaid Invoices')
            ->assertSee('Today Patient')
            ->assertSee('Future Patient')
            ->assertDontSee('₱500.00');
    }

    public function test_guest_is_redirected_to_login_and_admin_can_sign_in(): void
    {
        $admin = User::where('email', 'admin@clinic.local')->firstOrFail();

        $this->post(route('logout'))->assertRedirect(route('login'));
        $this->get('/')->assertRedirect(route('login'));
        $this->get(route('login'))->assertOk()->assertSee('CLINIC SYSTEM');

        $this->post(route('login.attempt'), [
            'username' => 'admin',
            'password' => 'admin',
        ])->assertRedirect(route('dashboard'));

        $this->assertAuthenticatedAs($admin);
    }

    public function test_invalid_login_is_rejected(): void
    {
        $this->post(route('logout'));

        $this->post(route('login.attempt'), [
            'username' => 'admin',
            'password' => 'wrong-password',
        ])->assertSessionHasErrors('username');

        $this->assertGuest();
    }

    public function test_invoice_edits_are_recorded_in_history(): void
    {
        $patient = Patient::create([
            'first_name' => 'Billing',
            'last_name' => 'Patient',
            'phone' => '09171234567',
        ]);
        $invoice = Invoice::create([
            'patient_id' => $patient->id,
            'amount' => 500,
            'status' => 'Unpaid',
            'due_date' => '2026-11-10',
        ]);

        $this->put(route('billing.update', $invoice->id), [
            'amount' => 650,
            'status' => 'Unpaid',
        ])->assertRedirect(route('billing.index'));

        $log = InvoiceChangeLog::where('invoice_id', $invoice->id)
            ->where('action', 'updated')
            ->firstOrFail();

        $this->assertSame(auth()->id(), $log->user_id);
        $this->assertEquals(500, $log->changes['amount']['old']);
        $this->assertEquals(650, $log->changes['amount']['new']);
        $this->assertNotNull($log->created_at);

        $this->get(route('billing.history', $invoice->id))
            ->assertOk()
            ->assertSee('INVOICE CHANGE HISTORY')
            ->assertSee('admin')
            ->assertSee('650');
    }

    public function test_unpriced_invoice_displays_dash_and_can_remain_blank(): void
    {
        $patient = Patient::create([
            'first_name' => 'Blank',
            'last_name' => 'Invoice',
            'phone' => '09171234567',
        ]);
        $invoice = Invoice::create([
            'patient_id' => $patient->id,
            'amount' => null,
            'status' => 'Unpaid',
            'due_date' => '2026-11-10',
        ]);

        $this->get(route('billing.index'))
            ->assertOk()
            ->assertSee('<td style="padding: 12px 15px;">-</td>', false);

        $this->get(route('billing.edit', $invoice->id))
            ->assertOk()
            ->assertSee('name="amount" value=""', false);

        $this->put(route('billing.update', $invoice->id), [
            'amount' => '',
            'status' => 'Unpaid',
        ])->assertRedirect(route('billing.index'));

        $this->assertDatabaseHas('invoices', [
            'id' => $invoice->id,
            'amount' => null,
            'status' => 'Unpaid',
        ]);
    }

    public function test_recording_payment_is_included_in_invoice_history(): void
    {
        $patient = Patient::create([
            'first_name' => 'Payment',
            'last_name' => 'Patient',
            'phone' => '09171234567',
        ]);
        $invoice = Invoice::create([
            'patient_id' => $patient->id,
            'amount' => 500,
            'status' => 'Unpaid',
            'due_date' => '2026-11-10',
        ]);

        $this->post(route('billing.store'), [
            'invoice_id' => $invoice->id,
            'amount_paid' => 500,
            'payment_method' => 'Cash',
        ])->assertRedirect(route('billing.index'));

        $this->assertDatabaseHas('invoices', [
            'id' => $invoice->id,
            'status' => 'Paid',
        ]);
        $this->assertDatabaseHas('invoice_change_logs', [
            'invoice_id' => $invoice->id,
            'user_id' => auth()->id(),
            'action' => 'payment_recorded',
        ]);
    }

    public function test_patient_show_page_uses_real_edit_and_delete_routes(): void
    {
        $patient = Patient::create([
            'first_name' => 'Alice',
            'last_name' => 'Wonder',
            'phone' => '09171234567',
        ]);

        $response = $this->get('/patients/' . $patient->id);

        $response->assertStatus(200);
        $response->assertSee(route('patients.edit', $patient->id), false);
        $response->assertSee(route('patients.destroy', $patient->id), false);
    }

    public function test_patient_can_be_edited(): void
    {
        $patient = Patient::create([
            'first_name' => 'Alice',
            'last_name' => 'Wonder',
            'phone' => '09171234567',
        ]);

        $this->get(route('patients.edit', $patient->id))
            ->assertOk()
            ->assertSee('value="Alice"', false);

        $this->put(route('patients.update', $patient->id), [
            'first_name' => 'Alicia',
            'last_name' => 'Wonder',
            'phone' => '09171234567',
        ])->assertRedirect(route('patients.show', $patient->id));

        $this->assertDatabaseHas('patients', [
            'id' => $patient->id,
            'first_name' => 'Alicia',
        ]);
    }

    public function test_daily_schedule_hides_completed_appointments_by_default(): void
    {
        $doctor = Doctor::create([
            'first_name' => 'John',
            'last_name' => 'Smith',
            'specialization' => 'General Practice',
            'phone' => '09181234567',
            'email' => 'john.smith@example.com',
        ]);

        $scheduledPatient = Patient::create([
            'first_name' => 'Scheduled',
            'last_name' => 'Visit',
            'phone' => '09171234567',
        ]);
        $completedPatient = Patient::create([
            'first_name' => 'Completed',
            'last_name' => 'Visit',
            'phone' => '09171234568',
        ]);

        Appointment::create([
            'patient_id' => $scheduledPatient->id,
            'doctor_id' => $doctor->id,
            'appointment_date' => '2026-10-30 09:00:00',
            'status' => 'Scheduled',
        ]);
        Appointment::create([
            'patient_id' => $completedPatient->id,
            'doctor_id' => $doctor->id,
            'appointment_date' => '2026-10-30 10:00:00',
            'status' => 'Completed',
        ]);

        $this->get(route('daily.index', ['date' => '2026-10-30']))
            ->assertOk()
            ->assertSee('SCHEDULED VISIT')
            ->assertDontSee('COMPLETED VISIT');

        $this->get(route('daily.index', ['date' => '2026-10-30', 'show_completed' => '1']))
            ->assertOk()
            ->assertSee('SCHEDULED VISIT')
            ->assertSee('COMPLETED VISIT');
    }

    public function test_mark_as_completed_action_submits_the_required_room(): void
    {
        $room = Room::create([
            'room_number' => '101',
            'room_type' => 'Consultation',
            'is_available' => true,
        ]);
        $patient = Patient::create([
            'first_name' => 'Taylor',
            'last_name' => 'Patient',
            'phone' => '09171234567',
        ]);
        $doctor = Doctor::create([
            'first_name' => 'Casey',
            'last_name' => 'Doctor',
            'specialization' => 'General Practice',
            'phone' => '09181234567',
            'email' => 'casey.doctor@example.com',
        ]);
        $appointment = Appointment::create([
            'patient_id' => $patient->id,
            'doctor_id' => $doctor->id,
            'room_id' => $room->id,
            'appointment_date' => '2026-11-10 09:00:00',
            'status' => 'Scheduled',
        ]);

        $this->get(route('appointments.show', $appointment->id))
            ->assertOk()
            ->assertSee('name="room_id" value="' . $room->id . '"', false);

        $this->put(route('appointments.update', $appointment->id), [
            'patient_id' => $patient->id,
            'doctor_id' => $doctor->id,
            'room_id' => $room->id,
            'appointment_date' => '2026-11-10 09:00:00',
            'status' => 'Completed',
        ])->assertRedirect(route('appointments.show', $appointment->id));

        $this->assertDatabaseHas('appointments', [
            'id' => $appointment->id,
            'status' => 'Completed',
        ]);
        $this->assertDatabaseHas('invoices', [
            'appointment_id' => $appointment->id,
            'status' => 'Unpaid',
            'amount' => null,
        ]);
    }

    public function test_same_room_cannot_be_double_booked_at_the_same_time(): void
    {
        $room = Room::create([
            'room_number' => 'A101',
            'room_type' => 'Consultation',
            'is_available' => true,
        ]);

        $patient = Patient::create([
            'first_name' => 'Jane',
            'last_name' => 'Doe',
            'phone' => '09171234567',
        ]);

        $doctor = Doctor::create([
            'first_name' => 'John',
            'last_name' => 'Smith',
            'specialization' => 'General Practice',
            'phone' => '09181234567',
            'email' => 'john.smith@example.com',
        ]);

        Appointment::create([
            'patient_id' => $patient->id,
            'doctor_id' => $doctor->id,
            'room_id' => $room->id,
            'appointment_date' => '2026-11-10 09:00:00',
            'status' => 'Scheduled',
        ]);

        $response = $this->post('/appointments', [
            'patient_id' => $patient->id,
            'doctor_id' => $doctor->id,
            'room_id' => $room->id,
            'appointment_date' => '2026-11-10 09:00:00',
            'notes' => 'Duplicate room booking attempt',
        ]);

        $response->assertSessionHasErrors(['room_id']);
        $this->assertDatabaseCount('appointments', 1);
    }

    public function test_room_seeder_preserves_existing_appointment_assignments(): void
    {
        $room = Room::create([
            'room_number' => '101',
            'room_type' => 'Consultation',
            'is_available' => true,
        ]);
        $patient = Patient::create([
            'first_name' => 'Seed',
            'last_name' => 'Patient',
            'phone' => '09171234567',
        ]);
        $doctor = Doctor::create([
            'first_name' => 'Seed',
            'last_name' => 'Doctor',
            'specialization' => 'General Practice',
            'phone' => '09181234567',
            'email' => 'seed.doctor@example.com',
        ]);
        $appointment = Appointment::create([
            'patient_id' => $patient->id,
            'doctor_id' => $doctor->id,
            'room_id' => $room->id,
            'appointment_date' => '2026-11-10 09:00:00',
            'status' => 'Scheduled',
        ]);

        $this->seed(RoomSeeder::class);

        $this->assertDatabaseHas('rooms', ['id' => $room->id]);
        $this->assertDatabaseHas('appointments', [
            'id' => $appointment->id,
            'room_id' => $room->id,
        ]);

        $this->seed(RoomSeeder::class);

        $this->assertDatabaseCount('rooms', 5);
    }
}
