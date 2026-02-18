<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Hostel;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Carbon;
use Inertia\Inertia;
use Inertia\Response;

class AdminController extends Controller
{
    public function dashboard(): Response
    {
        $managers = User::query()
            ->where('role', 'manager')
            ->where('is_active', false)
            ->orderByDesc('created_at')
            ->get(['id', 'name', 'email', 'phone', 'university', 'created_at']);

        $allManagers = User::query()
            ->where('role', 'manager')
            ->orderByDesc('created_at')
            ->get(['id', 'name', 'email', 'phone', 'university', 'is_active', 'approved_at', 'created_at']);

        return Inertia::render('Admin/Dashboard', [
            'stats' => [
                'totalUsers' => User::count(),
                'students' => User::where('role', 'student')->count(),
                'managers' => User::where('role', 'manager')->count(),
                'pendingManagerApprovals' => $managers->count(),
                'activeHostels' => Hostel::where('is_active', true)->count(),
                'pendingBookings' => Booking::where('status', 'pending_approval')->count(),
            ],
            'pendingManagers' => $managers->map(fn (User $user) => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone,
                'university' => $user->university,
                'requested_at' => optional($user->created_at)->toFormattedDateString(),
            ]),
            'managers' => $allManagers->map(fn (User $user) => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone,
                'university' => $user->university,
                'is_active' => (bool) $user->is_active,
                'approved_at' => $user->approved_at?->toFormattedDateString(),
                'requested_at' => optional($user->created_at)->toFormattedDateString(),
            ]),
        ]);
    }

    public function approveManager(User $user): RedirectResponse
    {
        abort_unless($user->role === 'manager', 422, 'Only manager accounts can be approved.');

        $user->update([
            'is_active' => true,
            'approved_at' => Carbon::now(),
        ]);

        return back()->with('success', 'Manager account approved successfully.');
    }

    public function suspendManager(User $user): RedirectResponse
    {
        abort_unless($user->role === 'manager', 422, 'Only manager accounts can be suspended.');

        $user->update([
            'is_active' => false,
        ]);

        return back()->with('success', 'Manager account suspended successfully.');
    }
}
