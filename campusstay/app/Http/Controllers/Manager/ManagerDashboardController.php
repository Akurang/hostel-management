<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Hostel;
use App\Models\Payment;
use App\Models\Room;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class ManagerDashboardController extends Controller
{
    public function index(): Response
    {
        $manager = Auth::user();

        $hostelIds = Hostel::query()
            ->where('manager_id', $manager->id)
            ->pluck('id');

        $totalBeds = Room::query()
            ->whereIn('hostel_id', $hostelIds)
            ->sum('total_beds');

        $availableBeds = Room::query()
            ->whereIn('hostel_id', $hostelIds)
            ->sum('available_beds');

        $occupiedBeds = max($totalBeds - $availableBeds, 0);

        $pendingApprovals = Booking::query()
            ->whereIn('hostel_id', $hostelIds)
            ->where('status', 'pending_approval')
            ->count();

        $confirmedBookings = Booking::query()
            ->whereIn('hostel_id', $hostelIds)
            ->where('status', 'confirmed')
            ->count();

        $successfulRevenue = Payment::query()
            ->where('status', 'success')
            ->whereHas('booking', fn ($query) => $query->whereIn('hostel_id', $hostelIds))
            ->sum('amount');

        $recentBookings = Booking::query()
            ->whereIn('hostel_id', $hostelIds)
            ->with(['student:id,name', 'hostel:id,name'])
            ->latest()
            ->limit(8)
            ->get()
            ->map(fn (Booking $booking) => [
                'id' => $booking->id,
                'reference' => $booking->reference,
                'student_name' => $booking->student?->name,
                'hostel_name' => $booking->hostel?->name,
                'status' => $booking->status,
                'created_at' => $booking->created_at?->toFormattedDateString(),
            ]);

        return Inertia::render('Manager/Dashboard', [
            'stats' => [
                'hostels' => $hostelIds->count(),
                'total_beds' => $totalBeds,
                'occupied_beds' => $occupiedBeds,
                'occupancy_rate' => $totalBeds > 0 ? round(($occupiedBeds / $totalBeds) * 100, 1) : 0,
                'pending_approvals' => $pendingApprovals,
                'confirmed_bookings' => $confirmedBookings,
                'successful_revenue' => (float) $successfulRevenue,
            ],
            'recentBookings' => $recentBookings,
        ]);
    }
}
