<?php

namespace App\Services\AI;

use Illuminate\Support\Facades\Route;

class DemoDataService
{
    /**
     * Generate demo response based on user message.
     */
    public function getDemoAnswer(string $message): string
    {
        $lower = strtolower($message);

        // ─── Sales ──────────────────────────────────────────────────────
        if ($this->containsAny($lower, ['sales', 'sale', 'today', 'bikri', 'revenue', 'income'])) {
            return $this->getDemoSales();
        }

        // ─── Stock ─────────────────────────────────────────────────────
        if ($this->containsAny($lower, ['stock', 'inventory', 'product', 'item', 'samagri', 'quantity'])) {
            return $this->getDemoStock();
        }

        // ─── Profit ────────────────────────────────────────────────────
        if ($this->containsAny($lower, ['profit', 'laabh', 'loss', 'expense', 'kharcha'])) {
            return $this->getDemoProfit();
        }

        // ─── Attendance ───────────────────────────────────────────────
        if ($this->containsAny($lower, ['attendance', 'attendence', 'student', 'present', 'absent'])) {
            return $this->getDemoAttendance();
        }

        // ─── Features / About ─────────────────────────────────────────
        if ($this->containsAny($lower, ['feature', 'what', 'help', 'capability', 'can you', 'do you'])) {
            return $this->getDemoFeatures();
        }

        // ─── Pricing ──────────────────────────────────────────────────
        if ($this->containsAny($lower, ['price', 'cost', 'pricing', 'fee', 'charge'])) {
            return $this->getDemoPricing();
        }

        // ─── Default ──────────────────────────────────────────────────
        return $this->getDefaultResponse();
    }

    // ─── Helper: Check if any keyword exists ─────────────────────────
    protected function containsAny(string $haystack, array $needles): bool
    {
        foreach ($needles as $needle) {
            if (stripos($haystack, $needle) !== false) {
                return true;
            }
        }
        return false;
    }

    // ─── Demo Responses (with compiled route URLs) ────────────────────

    protected function getDemoSales(): string
    {
        $loginUrl = route('login');
        $registerUrl = route('register');

        return "📊 **Today's Sales Report (Demo Data)**\n\n" .
               "📅 " . now()->format('d M Y') . "\n" .
               "💰 **Total Sales**: Rs. 45,200.00\n" .
               "📦 **Orders**: 8 completed orders\n" .
               "📈 **Last 7 Days**: Rs. 2,85,000.00\n" .
               "📊 **Growth**: +12.5% vs last week\n\n" .
               "✨ *This is demo data. Login to see your real sales.*\n\n" .
               "🔐 **Want real data?**\n" .
               "[Login]({$loginUrl}) | [Start Free Trial]({$registerUrl})";
    }

    protected function getDemoStock(): string
    {
        $loginUrl = route('login');
        $registerUrl = route('register');

        return "📦 **Stock Overview (Demo Data)**\n\n" .
               "| Product | Stock | Status |\n" .
               "|---------|-------|--------|\n" .
               "| Coca-Cola (500ml) | 31 | ✅ In Stock |\n" .
               "| Pepsi (500ml) | 12 | ✅ In Stock |\n" .
               "| Laptop | 5 | ⚠️ Low Stock |\n" .
               "| Mobile Phone | 8 | ✅ In Stock |\n" .
               "| Rice (10kg) | 0 | ❌ Out of Stock |\n\n" .
               "⚠️ *2 items are low on stock.*\n\n" .
               "✨ *This is demo data. Login to see your real inventory.*\n\n" .
               "🔐 **Want real data?**\n" .
               "[Login]({$loginUrl}) | [Start Free Trial]({$registerUrl})";
    }

    protected function getDemoProfit(): string
    {
        $loginUrl = route('login');
        $registerUrl = route('register');

        return "📈 **Today's Profit Report (Demo Data)**\n\n" .
               "💵 **Profit**: Rs. 12,500.00\n" .
               "💰 **Sales**: Rs. 45,200.00 (Revenue)\n" .
               "💸 **Expenses**: Rs. 32,700.00 (Costs)\n" .
               "📊 **Profit Margin**: 27.7%\n\n" .
               "📌 *Calculation: 45,200 - 32,700 = 12,500*\n\n" .
               "✨ *This is demo data. Login to see your real profit.*\n\n" .
               "🔐 **Want real data?**\n" .
               "[Login]({$loginUrl}) | [Start Free Trial]({$registerUrl})";
    }

    protected function getDemoAttendance(): string
    {
        $loginUrl = route('login');
        $registerUrl = route('register');

        return "🎓 **Attendance Summary (Demo Data)**\n\n" .
               "📅 " . now()->format('d M Y') . "\n" .
               "✅ Present: 42 students\n" .
               "❌ Absent: 8 students\n" .
               "👨‍🎓 Total Students: 50\n" .
               "📊 **Attendance Rate**: 84.0%\n\n" .
               "✨ *This is demo data. Login to see your real attendance.*\n\n" .
               "🔐 **Want real data?**\n" .
               "[Login]({$loginUrl}) | [Start Free Trial]({$registerUrl})";
    }

    protected function getDemoFeatures(): string
    {
        $loginUrl = route('login');
        $registerUrl = route('register');

        return "🤖 **BusinessOS AI Assistant**\n\n" .
               "I can help you with:\n" .
               "• 📊 **Sales** — Today's sales, trends, growth\n" .
               "• 📦 **Stock** — Inventory status, low stock alerts\n" .
               "• 💰 **Profit** — Profit/Loss calculation with breakdown\n" .
               "• 🎓 **Attendance** — Student/employee attendance\n" .
               "• 💡 **Business Tips** — General advice and insights\n\n" .
               "✨ *Try asking: 'Today's sales' or 'Stock status'*\n\n" .
               "🔐 **Login for real business data.**\n" .
               "[Login]({$loginUrl}) | [Start Free Trial]({$registerUrl})";
    }

    protected function getDemoPricing(): string
    {
        $registerUrl = route('register');

        return "💰 **BusinessOS Pricing (Demo)**\n\n" .
               "**Starter** — Free\n" .
               "• 100 Products\n" .
               "• 1 Branch\n" .
               "• Basic Reports\n\n" .
               "**Pro** — Rs. 999/mo\n" .
               "• Unlimited Products\n" .
               "• Unlimited Branches\n" .
               "• Advanced Reports\n" .
               "• Priority Support\n\n" .
               "**Enterprise** — Custom\n" .
               "• Everything in Pro\n" .
               "• Dedicated Support\n" .
               "• Custom Integrations\n\n" .
               "✨ *14-day free trial on Pro. No credit card required.*\n\n" .
               "[Start Free Trial]({$registerUrl})";
    }

    protected function getDefaultResponse(): string
    {
        $loginUrl = route('login');
        $registerUrl = route('register');

        return "🤖 **BusinessOS AI Assistant**\n\n" .
               "I'm your AI business assistant. I can help with:\n" .
               "• 📊 Sales reports\n" .
               "• 📦 Stock management\n" .
               "• 💰 Profit analysis\n" .
               "• 🎓 Attendance tracking\n" .
               "• 💡 Business insights\n\n" .
               "💡 **Try asking:**\n" .
               "• 'Today ko sales kati cha?'\n" .
               "• 'Low stock items haru dekhau'\n" .
               "• 'Profit kati cha?'\n" .
               "• 'What can you do?'\n\n" .
               "✨ *This is demo data. Login to access your real data.*\n\n" .
               "🔐 **Get started:**\n" .
               "[Login]({$loginUrl}) | [Start Free Trial]({$registerUrl})";
    }
}