<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class HostelResource extends JsonResource
{
    public static $wrap = null;

    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'description' => $this->description,
            'gender_policy' => $this->gender_policy,
            'distance_from_campus' => $this->distance_from_campus,
            'address' => $this->address,
            'university' => $this->university,
            'is_verified' => $this->is_verified,
            'rating' => $this->average_rating,
            'total_reviews' => $this->total_reviews,
            'room_types' => $this->rooms->map(fn ($room) => [
                'id' => $room->id,
                'type' => $room->type,
                'price_per_semester' => (float) $room->price_per_semester,
                'total_beds' => $room->total_beds,
                'available_beds' => $room->available_beds,
                'room_amenities' => $room->room_amenities ?? [],
            ]),
            'amenities' => $this->amenities->pluck('name')->values(),
            'images' => $this->images ?? [],
            'manager' => [
                'name' => $this->manager->name,
            ],
        ];
    }
}
