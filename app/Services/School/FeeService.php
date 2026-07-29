<?php

namespace App\Services\School;

use App\Models\School\FeeCollection;
use App\Models\School\Student;
use App\Models\School\FeeStructure;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class FeeService
{
    public function generateInvoices($studentId)
    {
        $student = Student::with('class')->findOrFail($studentId);
        $feeStructures = FeeStructure::where('school_class_id', $student->school_class_id)
            ->where('is_active', true)
            ->get();

        $invoices = [];
        foreach ($feeStructures as $fee) {
            $invoices[] = FeeCollection::create([
                'organization_id' => auth()->user()->organization_id,
                'school_student_id' => $student->id,
                'school_fee_structure_id' => $fee->id,
                'invoice_number' => 'INV-' . date('Ymd') . '-' . Str::random(6),
                'due_date' => now()->addDays(30),
                'amount' => $fee->amount,
                'status' => 'unpaid',
            ]);
        }

        return $invoices;
    }

    public function payInvoice(FeeCollection $invoice, array $data)
    {
        return DB::transaction(function () use ($invoice, $data) {
            $invoice->update([
                'paid_amount' => $data['amount'],
                'paid_date' => now(),
                'status' => $data['amount'] >= $invoice->amount ? 'paid' : 'partial',
                'payment_method' => $data['payment_method'],
                'notes' => $data['notes'] ?? null,
            ]);

            // Update student's total paid
            // Could also create a journal entry here

            return $invoice;
        });
    }

    public function getFeeSummary($studentId)
    {
        $student = Student::findOrFail($studentId);
        $invoices = $student->feeCollections;

        return [
            'total' => $invoices->sum('amount'),
            'paid' => $invoices->where('status', 'paid')->sum('paid_amount'),
            'due' => $invoices->whereIn('status', ['unpaid', 'partial'])->sum('amount'),
            'invoices' => $invoices,
        ];
    }
} 
