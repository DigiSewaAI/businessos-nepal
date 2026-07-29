<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Core Brand Identity
    |--------------------------------------------------------------------------
    */
    'brand_name' => 'BusinessOS',
    'company_name' => 'BusinessOS',
    'default_market' => 'np',
    'logo' => 'images/logo.png',          // relative path, asset() view मा use हुनेछ

    /*
    |--------------------------------------------------------------------------
    | Marketing Copy (Country‑specific overrides go in 'markets' array)
    |--------------------------------------------------------------------------
    */
    'hero_badge' => "Nepal's #1 SME Operating System",
    'hero_subtitle' => 'everything your Nepali business needs to grow',
    'industries_title' => 'Built for every Nepali business',
    'testimonials_title' => 'Trusted by Nepali businesses',
    'footer_description' => 'Empowering Nepali SMEs with modern technology.',

    'cta_button_text' => 'Start Free Trial',
    'cta_alt_button_text' => 'See How',

    /*
    |--------------------------------------------------------------------------
    | Footer & Copyright
    |--------------------------------------------------------------------------
    */
    'footer_copyright' => '© BusinessOS',

    /*
    |--------------------------------------------------------------------------
    | Country Badge (shown next to logo)
    |--------------------------------------------------------------------------
    */
    'country_badge' => 'Nepal', // empty for global

    /*
    |--------------------------------------------------------------------------
    | SEO Defaults
    |--------------------------------------------------------------------------
    */
    'meta_title' => 'BusinessOS - SME Operating System',
    'meta_description' => 'Inventory, POS, accounting, and reports for SMEs. Start free.',
    'meta_keywords' => 'SME, business management, inventory, POS, accounting, Nepal',

    'og_title' => 'BusinessOS - The Smart SME Operating System',
    'og_description' => 'The all-in-one platform for small businesses. Manage inventory, sales, accounting, and more.',
    'og_image' => 'images/og-image.jpg',   // relative
    'og_type' => 'website',
    'twitter_card' => 'summary_large_image',
    'twitter_site' => '@businessos',

    /*
    |--------------------------------------------------------------------------
    | Social Links
    |--------------------------------------------------------------------------
    */
    'social_links' => [
        'facebook' => 'https://facebook.com/businessos',
        'linkedin' => 'https://linkedin.com/company/businessos',
        'youtube' => 'https://youtube.com/businessos',
    ],

    /*
    |--------------------------------------------------------------------------
    | Contact
    |--------------------------------------------------------------------------
    */
    'contact_email' => 'support@businessos.com',

    /*
    |--------------------------------------------------------------------------
    | Future Market Overrides (V2)
    |--------------------------------------------------------------------------
    */
    'markets' => [
        // 'in' => [
        //     'hero_badge' => "India's #1 SME Operating System",
        //     ...
        // ],
    ],
];