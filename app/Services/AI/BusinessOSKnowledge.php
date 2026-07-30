<?php
namespace App\Services\AI;

class BusinessOSKnowledge
{
    public static function getContext(): array
    {
        return [
            'about' => 'BusinessOS is Nepal\'s #1 SME Operating System. Built for Nepali businesses — Retail, Wholesale, Electronics, Hardware, Furniture, Bakery, Gym, Travel, NGO, Cooperative, Agriculture, Auto Parts, Beauty Salon, Printing Press, Manufacturing.',
            
            'pricing' => [
                'starter' => 'Free: 100 Products, 1 Branch, Basic Reports, POS Access',
                'pro' => 'Rs. 999/month: Unlimited Products, Unlimited Branches, Advanced Reports, Priority Support, Purchase & Finance Modules',
                'enterprise' => 'Custom: Everything in Pro, Dedicated Support, Custom Integrations, API Access',
                'trial' => '14-day free trial on Pro. No credit card required.'
            ],
            
            'features' => [
                'Inventory Management' => 'Manage products, variants, SKUs, and barcodes across multiple warehouses. Real-time stock updates.',
                'Point of Sale (POS)' => 'Fast, intuitive POS with discounts, taxes, and instant receipt printing. Works offline-ready.',
                'Reports & Analytics' => 'Top-selling products, profit margins, and daily cash flow with visual dashboards.',
                'Purchase & Suppliers' => 'Manage purchase orders, supplier history, and track payables.',
                'Cashbook & Expenses' => 'Track every expense, manage daily cash closing, ledger-ready foundation.',
                'Multi-Branch & Roles' => 'Unlimited branches, staff roles, granular permissions.',
                'AI Ready' => 'Smart insights and forecasts. Predict low stock alerts and auto-categorize products.',
                'Nepali & English' => 'Full Nepali (नेपाली) and English interface. Switch anytime.',
                'Cloud Backup' => 'Automatic, secure daily backups.',
                'Barcode Ready' => 'Generate and scan barcodes. SKU auto-generation.',
                'Mobile Optimized' => 'Fully responsive. Works on any smartphone, tablet, or desktop.',
                'Enterprise Security' => 'Role-based access, audit logs, data encryption.'
            ],
            
            'industries' => ['Retail', 'Wholesale', 'Electronics', 'Hardware', 'Furniture', 'Bakery', 'Gym', 'Travel', 'NGO', 'Cooperative', 'Agriculture', 'Auto Parts', 'Beauty Salon', 'Printing Press', 'Manufacturing'],
            
            'stats' => [
                'trusted_by' => '500+ SMEs',
                'processed' => 'Rs. 10M+',
                'uptime' => '99.9%',
                'rating' => '4.8★'
            ],
            
            'support' => 'Pro plan includes priority support. Enterprise includes dedicated support. Contact admin@businessos.com for custom support.',
            
            'faq' => [
                'Can I use BusinessOS on mobile?' => 'Yes, fully responsive. Works on any smartphone, tablet, or desktop.',
                'Is my data secure?' => 'Role-based access, audit logs, and data encryption. Built on Laravel\'s security standards.',
                'Can I upgrade or cancel anytime?' => 'Yes. Pro plan includes 14-day free trial. No credit card required.'
            ]
        ];
    }
}