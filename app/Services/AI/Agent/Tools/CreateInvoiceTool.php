<?php
namespace App\Services\AI\Agent\Tools;

use App\Services\AI\Agent\Contracts\ToolInterface;
use App\Models\Invoice;
use App\Models\Registration;
use Illuminate\Support\Str;

class CreateInvoiceTool implements ToolInterface
{
    public function getName(): string
    {
        return 'create_invoice';
    }

    public function getDescription(): string
    {
        return 'Create a new invoice for a registration/hostel';
    }

    public function getParameters(): array
    {
        return [
            'registration_id' => 'integer|required',
            'amount' => 'numeric|required',
            'invoice_type' => 'string|nullable',
        ];
    }

    public function execute(array $params): array
    {
        $registration = Registration::find($params['registration_id']);
        if (!$registration) {
            return ['error' => 'Registration not found'];
        }

        $invoiceNumber = 'INV-' . date('Y') . '-' . Str::random(6);

        $invoice = Invoice::create([
            'registration_id' => $registration->id,
            'invoice_number' => $invoiceNumber,
            'amount' => $params['amount'],
            'issued_date' => now(),
            'due_date' => now()->addDays(30),
            'status' => 'pending',
            'invoice_type' => $params['invoice_type'] ?? 'manual',
        ]);

        return [
            'invoice_id' => $invoice->id,
            'invoice_number' => $invoiceNumber,
            'amount' => $invoice->amount,
            'status' => $invoice->status,
            'message' => "Invoice {$invoiceNumber} created successfully!",
        ];
    }
}