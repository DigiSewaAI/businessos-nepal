<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Industry Definitions
    |--------------------------------------------------------------------------
    |
    | This file defines all supported industries, their business categories,
    | icons, colors, and default dashboard/sidebar mappings.
    |
    | To add a new industry, simply add a new entry here.
    | No migration needed.
    |
    */

    'industries' => [
        'retail' => [
            'label' => 'Retail',
            'icon' => 'fa-store',
            'color' => '#3b82f6',
            'business_categories' => [
                'general' => 'General Retail',
                'pharmacy' => 'Pharmacy',
                'electronics' => 'Electronics',
                'bakery' => 'Bakery',
                'supermarket' => 'Supermarket',
                'hardware' => 'Hardware',
                'furniture' => 'Furniture',
                'mobile_shop' => 'Mobile Shop',
                'cosmetics' => 'Cosmetics',
            ],
            'default_dashboard' => 'retail',
            'default_sidebar' => 'retail',
        ],
        'school' => [
            'label' => 'School',
            'icon' => 'fa-school',
            'color' => '#10b981',
            'business_categories' => [
                'general' => 'General School',
                'nursery' => 'Nursery',
                'primary' => 'Primary School',
                'secondary' => 'Secondary School',
                'college' => 'College',
                'training' => 'Training Center',
            ],
            'default_dashboard' => 'school',
            'default_sidebar' => 'school',
        ],
        'restaurant' => [
            'label' => 'Restaurant',
            'icon' => 'fa-utensils',
            'color' => '#ef4444',
            'business_categories' => [
                'general' => 'General Restaurant',
                'cafe' => 'Cafe',
                'bakery' => 'Bakery',
                'fast_food' => 'Fast Food',
                'fine_dining' => 'Fine Dining',
            ],
            'default_dashboard' => 'restaurant',
            'default_sidebar' => 'restaurant',
        ],
        'travel' => [
            'label' => 'Travel & Trekking',
            'icon' => 'fa-plane',
            'color' => '#8b5cf6',
            'business_categories' => [
                'general' => 'General Travel',
                'travel_agency' => 'Travel Agency',
                'trekking' => 'Trekking Agency',
                'tour_operator' => 'Tour Operator',
            ],
            'default_dashboard' => 'travel',
            'default_sidebar' => 'travel',
        ],
        'hospital' => [
            'label' => 'Hospital / Clinic',
            'icon' => 'fa-hospital',
            'color' => '#ef4444',
            'business_categories' => [
                'general' => 'General Hospital',
                'clinic' => 'Clinic',
                'pharmacy' => 'Pharmacy',
            ],
            'default_dashboard' => 'hospital',
            'default_sidebar' => 'hospital',
        ],
        'ngo' => [
            'label' => 'NGO / Cooperative',
            'icon' => 'fa-hand-holding-heart',
            'color' => '#f59e0b',
            'business_categories' => [
                'general' => 'General NGO',
                'ingo' => 'INGO',
                'cooperative' => 'Cooperative',
            ],
            'default_dashboard' => 'ngo',
            'default_sidebar' => 'ngo',
        ],
        'manufacturing' => [
            'label' => 'Manufacturing',
            'icon' => 'fa-industry',
            'color' => '#6b7280',
            'business_categories' => [
                'general' => 'General Manufacturing',
                'food_processing' => 'Food Processing',
                'small_manufacturing' => 'Small Manufacturing',
            ],
            'default_dashboard' => 'manufacturing',
            'default_sidebar' => 'manufacturing',
        ],
        'service' => [
            'label' => 'Service',
            'icon' => 'fa-hand-sparkles',
            'color' => '#ec4899',
            'business_categories' => [
                'general' => 'General Service',
                'gym' => 'Gym',
                'salon' => 'Salon / Parlour',
                'spa' => 'Spa',
            ],
            'default_dashboard' => 'service',
            'default_sidebar' => 'service',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Feature Flags
    |--------------------------------------------------------------------------
    |
    | These flags control whether new industry features are active.
    | Default is false for backward compatibility.
    |
    */
    'features' => [
        'industry' => env('FEATURE_INDUSTRY', false),
        'dynamic_dashboard' => env('FEATURE_DYNAMIC_DASHBOARD', false),
        'dynamic_sidebar' => env('FEATURE_DYNAMIC_SIDEBAR', false),
        'media_library' => env('FEATURE_MEDIA_LIBRARY', false),
    ],
];