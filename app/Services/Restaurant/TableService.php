<?php

namespace App\Services\Restaurant;

use App\Models\RestaurantTable;
use Illuminate\Support\Facades\DB;

class TableService
{
    public function create(array $data)
    {
        $data['organization_id'] = auth()->user()->organization_id;
        $data['branch_id'] = auth()->user()->branch_id ?? $data['branch_id'];
        return RestaurantTable::create($data);
    }

    public function update(RestaurantTable $table, array $data)
    {
        $table->update($data);
        return $table;
    }

    public function toggleStatus(RestaurantTable $table, $status)
    {
        if ($status === 'occupied' && $table->activeOrder()) {
            throw new \Exception('Table already has an active order.');
        }
        $table->update(['status' => $status]);
        return $table;
    }

    public function getAvailableTables()
    {
        return RestaurantTable::where('organization_id', auth()->user()->organization_id)
            ->where('status', 'available')
            ->where('is_active', true)
            ->orderBy('number')
            ->get();
    }

    public function getTableLayout()
    {
        return RestaurantTable::where('organization_id', auth()->user()->organization_id)
            ->where('is_active', true)
            ->orderBy('number')
            ->get()
            ->groupBy(function ($table) {
                // group by floor or area if needed
                return 'Main Floor';
            });
    }
} 
