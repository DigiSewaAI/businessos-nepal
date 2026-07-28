<?php

namespace Database\Seeders;

use App\Models\Unit;
use Illuminate\Database\Seeder;

class DefaultInventorySeeder extends Seeder
{
    public function run(): void
    {
        $units = [
            ['name' => 'Piece', 'code' => 'PCS', 'symbol' => 'pc', 'is_default' => true],
            ['name' => 'Kilogram', 'code' => 'KG', 'symbol' => 'kg', 'is_default' => false],
            ['name' => 'Box', 'code' => 'BOX', 'symbol' => 'box', 'is_default' => false],
            ['name' => 'Liter', 'code' => 'LTR', 'symbol' => 'l', 'is_default' => false],
            ['name' => 'Meter', 'code' => 'MTR', 'symbol' => 'm', 'is_default' => false],
            ['name' => 'Gram', 'code' => 'GM', 'symbol' => 'g', 'is_default' => false],
            ['name' => 'Dozen', 'code' => 'DZN', 'symbol' => 'dz', 'is_default' => false],
        ];

        foreach ($units as $unit) {
            Unit::create($unit);
        }
    }
}