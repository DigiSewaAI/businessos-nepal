<?php
namespace App\Services\AI\Context;

class IndustryContextFactory
{
    protected $contexts = [
        'school' => SchoolContext::class,
        'retail' => RetailContext::class,
        'restaurant' => RestaurantContext::class,
        'travel' => TravelContext::class,
        'ngo' => NGOContext::class,
        'manufacturing' => ManufacturingContext::class,
        'hospital' => HospitalContext::class,
        'service' => ServiceContext::class,
    ];

    public function getContext(string $industry, int $orgId): array
    {
        $industry = $industry ?? 'retail';
        
        if (!isset($this->contexts[$industry])) {
            $industry = 'retail';
        }
        
        $class = $this->contexts[$industry];
        $instance = new $class();
        
        return $instance->getData($orgId);
    }
    
    public function getSupportedIndustries(): array
    {
        return array_keys($this->contexts);
    }
    
    public function getKeywords(string $industry): array
    {
        $keywords = [
            'school' => ['student', 'teacher', 'attendance', 'fee', 'exam', 'class', 'grade', 'school', 'पढाई', 'विद्यालय', 'शिक्षक', 'विद्यार्थी', 'परीक्षा'],
            'retail' => ['sales', 'product', 'stock', 'inventory', 'price', 'purchase', 'expense', 'profit', 'बिक्री', 'स्टक', 'उत्पादन'],
            'restaurant' => ['order', 'table', 'kot', 'kitchen', 'menu', 'dish', 'revenue', 'आदेश', 'टेबल', 'किचन', 'खाना'],
            'travel' => ['booking', 'package', 'tour', 'destination', 'trekking', 'trip', 'flight', 'यात्रा', 'बुकिङ', 'ट्रेकिङ'],
            'ngo' => ['project', 'donation', 'beneficiary', 'program', 'fund', 'योजना', 'दान', 'सहयोग'],
            'manufacturing' => ['production', 'manufacturing', 'quality', 'raw', 'material', 'उत्पादन', 'निर्माण'],
            'hospital' => ['patient', 'doctor', 'appointment', 'medicine', 'treatment', 'बिरामी', 'डाक्टर', 'उपचार'],
            'service' => ['client', 'service', 'booking', 'appointment', 'ग्राहक', 'सेवा'],
        ];
        
        return $keywords[$industry] ?? [];
    }
    
    public function getBuilders(): array
    {
        return [
            'school' => 'buildSchoolResponse',
            'retail' => 'buildRetailResponse',
            'restaurant' => 'buildRestaurantResponse',
            'travel' => 'buildTravelResponse',
            'ngo' => 'buildNGOResponse',
            'manufacturing' => 'buildManufacturingResponse',
            'hospital' => 'buildHospitalResponse',
            'service' => 'buildServiceResponse',
        ];
    }
}