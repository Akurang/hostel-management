<?php

namespace Database\Seeders;

use App\Models\Amenity;
use Illuminate\Database\Seeder;

class AmenitySeeder extends Seeder
{
    public function run(): void
    {
        $amenities = [
            ['name' => 'WiFi', 'icon' => 'wifi'],
            ['name' => 'Laundry', 'icon' => 'shirt'],
            ['name' => 'Kitchen', 'icon' => 'flame'],
            ['name' => 'Study Room', 'icon' => 'book-open'],
            ['name' => 'Generator', 'icon' => 'zap'],
            ['name' => 'Security', 'icon' => 'shield'],
            ['name' => 'CCTV', 'icon' => 'camera'],
            ['name' => 'Water 24/7', 'icon' => 'droplets'],
            ['name' => 'Gym', 'icon' => 'dumbbell'],
            ['name' => 'Parking', 'icon' => 'car'],
            ['name' => 'Common Room', 'icon' => 'sofa'],
            ['name' => 'Cafeteria', 'icon' => 'utensils'],
        ];

        foreach ($amenities as $amenity) {
            Amenity::updateOrCreate(['name' => $amenity['name']], $amenity);
        }
    }
}
