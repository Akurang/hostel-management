<?php

namespace App\Http\Controllers;

use App\Http\Resources\HostelResource;
use App\Models\Amenity;
use App\Models\Hostel;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class HostelController extends Controller
{
    public function index(Request $request): Response
    {
        $query = Hostel::query()
            ->where('is_active', true)
            ->with(['rooms', 'amenities', 'reviews', 'manager'])
            ->withCount('reviews');

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search): void {
                $q->where('name', 'ilike', "%{$search}%")
                    ->orWhere('address', 'ilike', "%{$search}%");
            });
        }

        if (($gender = $request->input('gender_policy')) && $gender !== 'all') {
            $query->where('gender_policy', $gender);
        }

        if (($roomType = $request->input('room_type')) && $roomType !== 'all') {
            $types = is_array($roomType) ? $roomType : [$roomType];
            $query->whereHas('rooms', function ($q) use ($types): void {
                $q->whereIn('type', $types)->where('is_active', true);
            });
        }

        if ($maxPrice = $request->input('max_price')) {
            $query->whereHas('rooms', function ($q) use ($maxPrice): void {
                $q->where('price_per_semester', '<=', $maxPrice);
            });
        }

        $amenities = $request->input('amenities', []);
        if (is_array($amenities) && count($amenities) > 0) {
            foreach ($amenities as $amenity) {
                $query->whereHas('amenities', function ($q) use ($amenity): void {
                    $q->where('name', $amenity);
                });
            }
        }

        $hostels = $query->get();

        return Inertia::render('Listings', [
            'hostels' => HostelResource::collection($hostels),
            'filters' => $request->only(['search', 'gender_policy', 'room_type', 'max_price', 'amenities']),
            'allAmenities' => Amenity::orderBy('name')->pluck('name'),
        ]);
    }

    public function show(Hostel $hostel): Response
    {
        $hostel->load(['rooms', 'amenities', 'manager', 'reviews.student']);

        return Inertia::render('HostelDetail', [
            'hostel' => new HostelResource($hostel),
            'reviews' => $hostel->reviews->where('is_visible', true)->map(fn ($review) => [
                'id' => $review->id,
                'student_name' => $review->student->name,
                'avatar_initial' => strtoupper(substr($review->student->name, 0, 1)),
                'rating' => $review->rating,
                'comment' => $review->comment,
                'date' => $review->created_at->format('F Y'),
                'is_verified_student' => true,
            ])->values(),
        ]);
    }
}
