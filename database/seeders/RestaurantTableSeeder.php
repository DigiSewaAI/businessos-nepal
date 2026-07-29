<?php

namespace Database\Seeders;

use App\Models\RestaurantTable;
use Illuminate\Database\Seeder;

class RestaurantTableSeeder extends Seeder
{
    public function run()
    {
        $orgId = 2; // Ashish Virtual Restaurant

        for ($i = 1; $i <= 20; $i++) {
            RestaurantTable::create([
                'organization_id' => $orgId,
                'branch_id' => 2,
                'number' => 'Table ' . $i,
                'capacity' => rand(2, 6),
                'status' => 'available',
                'is_active' => true,
            ]);
        }
    }
}