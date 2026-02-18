<?php

namespace Database\Seeders;

use App\Models\Amenity;
use App\Models\Hostel;
use App\Models\Room;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class HostelSeeder extends Seeder
{
    public function run(): void
    {
        $managerEmails = [
            'manager1@campusstay.com',
            'manager2@campusstay.com',
            'manager3@campusstay.com',
        ];

        $managers = User::query()
            ->whereIn('email', $managerEmails)
            ->get()
            ->keyBy('email');

        $images = [
            'https://images.unsplash.com/photo-1555854877-bab0e564b8d5?w=800',
            'https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?w=800',
            'https://images.unsplash.com/photo-1560448204-e02f11c3d0e2?w=800',
            'https://images.unsplash.com/photo-1502672260266-1c1ef2d93688?w=800',
        ];

        $hostels = [
            [
                'name' => 'Nkrumah Executive Suites',
                'manager_email' => 'manager1@campusstay.com',
                'gender_policy' => 'mixed',
                'university' => 'KNUST',
                'address' => 'Ayigya, Kumasi, Ashanti Region',
                'distance_from_campus' => '5 mins walk',
                'is_verified' => true,
                'description' => 'Nkrumah Executive Suites offers a secure and comfortable stay for students near KNUST. The rooms are well-maintained with stable utilities and focused study spaces. It is ideal for students seeking convenience and calm within walking distance of campus.',
                'rooms' => [
                    ['type' => '1-in-a-room', 'price_per_semester' => 3800, 'total_beds' => 20, 'available_beds' => 8],
                    ['type' => '2-in-a-room', 'price_per_semester' => 2400, 'total_beds' => 40, 'available_beds' => 15],
                ],
                'amenities' => ['WiFi', 'Generator', 'Security', 'CCTV', 'Water 24/7', 'Study Room', 'Parking'],
            ],
            [
                'name' => 'Legon Heights Residence',
                'manager_email' => 'manager2@campusstay.com',
                'gender_policy' => 'female',
                'university' => 'UG',
                'address' => 'Accra, Greater Accra Region, near University of Ghana',
                'distance_from_campus' => '3 mins walk',
                'is_verified' => true,
                'description' => 'Legon Heights Residence is tailored for female students at UG who want safety and proximity. The hostel blends modern amenities with strong management support and reliable services. It creates a balanced environment for academic focus and social comfort.',
                'rooms' => [
                    ['type' => '1-in-a-room', 'price_per_semester' => 4200, 'total_beds' => 15, 'available_beds' => 3],
                    ['type' => '2-in-a-room', 'price_per_semester' => 2800, 'total_beds' => 30, 'available_beds' => 0],
                    ['type' => '3-in-a-room', 'price_per_semester' => 1800, 'total_beds' => 45, 'available_beds' => 12],
                ],
                'amenities' => ['WiFi', 'Laundry', 'Kitchen', 'Security', 'CCTV', 'Common Room', 'Cafeteria'],
            ],
            [
                'name' => 'Cape Coast Student Lodge',
                'manager_email' => 'manager3@campusstay.com',
                'gender_policy' => 'male',
                'university' => 'UCC',
                'address' => 'University Road, Cape Coast, Central Region',
                'distance_from_campus' => '8 mins walk',
                'is_verified' => false,
                'description' => 'Cape Coast Student Lodge provides affordable accommodation built for UCC students. The hostel prioritizes practical services, clean shared facilities, and dependable security. It is a popular value option for students who want accessibility and flexibility.',
                'rooms' => [
                    ['type' => '2-in-a-room', 'price_per_semester' => 2000, 'total_beds' => 50, 'available_beds' => 20],
                    ['type' => '3-in-a-room', 'price_per_semester' => 1500, 'total_beds' => 60, 'available_beds' => 35],
                ],
                'amenities' => ['WiFi', 'Generator', 'Water 24/7', 'Parking', 'Security'],
            ],
            [
                'name' => 'Kotoka Premium Flats',
                'manager_email' => 'manager1@campusstay.com',
                'gender_policy' => 'mixed',
                'university' => 'KNUST',
                'address' => 'Bomso, Kumasi, Ashanti Region',
                'distance_from_campus' => '10 mins walk',
                'is_verified' => true,
                'description' => 'Kotoka Premium Flats is a premium mixed hostel in Bomso serving KNUST students. Residents enjoy modern fittings, gym access, and enhanced facility management throughout the semester. It suits students who value privacy and high comfort standards.',
                'rooms' => [
                    ['type' => '1-in-a-room', 'price_per_semester' => 4500, 'total_beds' => 10, 'available_beds' => 2],
                    ['type' => '2-in-a-room', 'price_per_semester' => 3000, 'total_beds' => 20, 'available_beds' => 8],
                ],
                'amenities' => ['WiFi', 'Gym', 'Laundry', 'Generator', 'Security', 'CCTV', 'Water 24/7', 'Parking'],
            ],
            [
                'name' => 'Adenta Student Inn',
                'manager_email' => 'manager2@campusstay.com',
                'gender_policy' => 'female',
                'university' => 'UG',
                'address' => 'Adenta, Accra, Greater Accra Region',
                'distance_from_campus' => '15 mins drive',
                'is_verified' => false,
                'description' => 'Adenta Student Inn supports UG students looking for affordable spaces outside central Legon. The hostel offers reliable water supply, practical shared amenities, and a calm study atmosphere. Commuting is easy through regular campus transport routes.',
                'rooms' => [
                    ['type' => '2-in-a-room', 'price_per_semester' => 2200, 'total_beds' => 35, 'available_beds' => 18],
                    ['type' => '3-in-a-room', 'price_per_semester' => 1600, 'total_beds' => 45, 'available_beds' => 0],
                ],
                'amenities' => ['WiFi', 'Kitchen', 'Laundry', 'Common Room', 'Water 24/7'],
            ],
            [
                'name' => 'Mensah Sarbah Annex',
                'manager_email' => 'manager3@campusstay.com',
                'gender_policy' => 'mixed',
                'university' => 'UG',
                'address' => 'East Legon, Accra, Greater Accra Region',
                'distance_from_campus' => '7 mins walk',
                'is_verified' => true,
                'description' => 'Mensah Sarbah Annex is a mixed hostel designed for students who want proximity and quality service. The hostel includes structured security, study-focused spaces, and well-managed room inventory. It remains one of the most in-demand options near UG.',
                'rooms' => [
                    ['type' => '1-in-a-room', 'price_per_semester' => 3500, 'total_beds' => 12, 'available_beds' => 5],
                    ['type' => '2-in-a-room', 'price_per_semester' => 2500, 'total_beds' => 24, 'available_beds' => 10],
                    ['type' => '3-in-a-room', 'price_per_semester' => 1700, 'total_beds' => 36, 'available_beds' => 20],
                ],
                'amenities' => ['WiFi', 'Security', 'Generator', 'Study Room', 'Cafeteria', 'CCTV'],
            ],
        ];

        foreach ($hostels as $index => $hostelData) {
            $hostel = Hostel::updateOrCreate(
                ['slug' => Str::slug($hostelData['name'])],
                [
                    'manager_id' => $managers[$hostelData['manager_email']]->id,
                    'name' => $hostelData['name'],
                    'description' => $hostelData['description'],
                    'gender_policy' => $hostelData['gender_policy'],
                    'address' => $hostelData['address'],
                    'distance_from_campus' => $hostelData['distance_from_campus'],
                    'university' => $hostelData['university'],
                    'is_verified' => $hostelData['is_verified'],
                    'is_active' => true,
                    'images' => [
                        $images[$index % 4],
                        $images[($index + 1) % 4],
                        $images[($index + 2) % 4],
                        $images[($index + 3) % 4],
                    ],
                ],
            );

            Room::query()->where('hostel_id', $hostel->id)->delete();

            foreach ($hostelData['rooms'] as $room) {
                $hostel->rooms()->create([
                    'type' => $room['type'],
                    'price_per_semester' => $room['price_per_semester'],
                    'total_beds' => $room['total_beds'],
                    'available_beds' => $room['available_beds'],
                    'is_active' => true,
                ]);
            }

            $amenityIds = Amenity::query()
                ->whereIn('name', $hostelData['amenities'])
                ->pluck('id')
                ->all();

            $hostel->amenities()->sync($amenityIds);
        }
    }
}
