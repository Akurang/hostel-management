<?php

namespace App\Http\Controllers;

use App\Http\Resources\HostelResource;
use App\Models\Hostel;
use Inertia\Inertia;
use Inertia\Response;

class HomeController extends Controller
{
    public function index(): Response
    {
        $featuredHostels = Hostel::query()
            ->where('is_active', true)
            ->where('is_verified', true)
            ->with(['rooms', 'amenities', 'reviews', 'manager'])
            ->withCount('reviews')
            ->limit(3)
            ->get();

        return Inertia::render('Home', [
            'featuredHostels' => HostelResource::collection($featuredHostels),
        ]);
    }
}
