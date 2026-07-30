<?php
namespace Database\Seeders;

use App\Models\AI\FAQ;
use Illuminate\Database\Seeder;

class AIFAQSeeder extends Seeder
{
    public function run()
    {
        $faqs = [
            // About BusinessOS
            ['category' => 'about', 'question' => 'BusinessOS के हो?', 'answer' => 'BusinessOS Nepal\'s #1 SME Operating System हो। Inventory, POS, Accounting, Reports — सबै एकै ठाउँमा। 500+ businesses trust us.'],
            ['category' => 'about', 'question' => 'BusinessOS कसले बनाएको हो?', 'answer' => 'BusinessOS Nepal मा बनेको हो। यो Nepali SMEs को लागि विशेष रूपमा डिजाइन गरिएको platform हो।'],
            ['category' => 'about', 'question' => 'BusinessOS को mission के हो?', 'answer' => 'Nepali SMEs लाई affordable, powerful, र easy-to-use technology प्रदान गर्नु। Digital Nepal को सपना साकार पार्नु।'],
            
            // Features
            ['category' => 'features', 'question' => 'के के features छन् BusinessOS मा?', 'answer' => 'Inventory Management, Point of Sale (POS), Reports & Analytics, Purchase & Suppliers, Cashbook & Expenses, Multi-Branch & Roles, AI Ready, Nepali & English interface, Cloud Backup, Barcode Ready, Mobile Optimized, Enterprise Security।'],
            ['category' => 'features', 'question' => 'AI Ready भनेको के हो?', 'answer' => 'AI Ready भनेको BusinessOS मा built-in AI Assistant छ — जसले smart insights, forecasts, low stock alerts, र auto-categorization प्रदान गर्छ।'],
            ['category' => 'features', 'question' => 'Nepali मा use गर्न मिल्छ?', 'answer' => 'हो! BusinessOS पूर्ण रूपमा Nepali (नेपाली) र English मा उपलब्ध छ। तपाईं जुनसुकै समय switch गर्न सक्नुहुन्छ।'],
            ['category' => 'features', 'question' => 'Multi-branch support छ?', 'answer' => 'हो! Unlimited branches, एक dashboard, र granular permissions। तपाईंको पूरै team लाई securely manage गर्न सक्नुहुन्छ।'],
            
            // Pricing
            ['category' => 'pricing', 'question' => 'Pricing कति छ?', 'answer' => 'Starter: Free (100 products, 1 branch, basic reports). Pro: Rs. 999/month (unlimited products, branches, advanced reports, priority support). Enterprise: Custom (dedicated support, API access)'],
            ['category' => 'pricing', 'question' => 'Free trial कति दिनको?', 'answer' => '14-day free trial on Pro. No credit card required.'],
            ['category' => 'pricing', 'question' => 'Pro plan मा के पाइन्छ?', 'answer' => 'Unlimited Products, Unlimited Branches, Advanced Reports, Priority Support, Purchase & Finance Modules।'],
            
            // Industries
            ['category' => 'industries', 'question' => 'कुन industry को लागि BusinessOS हो?', 'answer' => 'Retail, Wholesale, Electronics, Hardware, Furniture, Bakery, Gym, Travel, NGO, Cooperative, Agriculture, Auto Parts, Beauty Salon, Printing Press, Manufacturing।'],
            
            // Support
            ['category' => 'support', 'question' => 'Support कसरी पाउने?', 'answer' => 'Pro plan मा priority support। Enterprise मा dedicated support। Email: admin@businessos.com'],
            ['category' => 'support', 'question' => 'Help Center कहाँ छ?', 'answer' => 'Help Center मा FAQs, guides, र tutorials छन्। Visit: /help'],
            
            // Technical
            ['category' => 'technical', 'question' => 'Data सुरक्षित छ?', 'answer' => 'हो! Role-based access, audit logs, data encryption, र automatic daily backups। Built on Laravel\'s security standards।'],
            ['category' => 'technical', 'question' => 'Cloud backup छ?', 'answer' => 'हो! Automatic, secure daily backups। तपाईंको data safe र recoverable छ।'],
            ['category' => 'technical', 'question' => 'API छ?', 'answer' => 'Enterprise plan मा API access available छ। Custom integrations को लागि।'],
        ];

        foreach ($faqs as $faq) {
            FAQ::updateOrCreate(
                ['question' => $faq['question']],
                $faq + ['keywords' => explode(' ', $faq['question']), 'is_active' => true]
            );
        }
    }
}