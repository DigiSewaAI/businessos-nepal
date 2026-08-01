@extends('layouts.admin')

@section('title', 'Create Journal Entry')

@section('content')
<div class="pt-4 pb-4 px-4 bg-gray-50 min-h-screen">
    <div class="max-w-5xl mx-auto">
        <div class="flex items-center mb-6">
            <a href="{{ route('journal-entries.index') }}" class="text-gray-500 hover:text-gray-700 mr-4">
                <i class="fa-solid fa-arrow-left"></i>
            </a>
            <h1 class="text-2xl font-bold text-gray-900">New Journal Entry</h1>
        </div>

        <form action="{{ route('journal-entries.store') }}" method="POST" id="journalForm">
            @csrf

            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 mb-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Date <span class="text-red-500">*</span></label>
                        <input type="date" name="date" value="{{ old('date', now()->toDateString()) }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Description <span class="text-red-500">*</span></label>
                        <input type="text" name="description" value="{{ old('description') }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500" required>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 bg-gray-50 border-b border-gray-200 flex justify-between items-center">
                    <h3 class="text-lg font-semibold text-gray-800">Journal Lines</h3>
                    <button type="button" onclick="addLine()" class="text-blue-600 hover:text-blue-800 text-sm font-semibold">
                        <i class="fa-solid fa-plus"></i> Add Line
                    </button>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full" id="linesTable">
                        <thead class="bg-gray-50 border-b border-gray-200">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase w-5/12">Account</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase w-2/12">Debit</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase w-2/12">Credit</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase w-2/12">Description</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase w-1/12">Action</th>
                            </tr>
                        </thead>
                        <tbody id="linesBody" class="divide-y divide-gray-100">
                            <tr class="line-row">
                                <td class="px-4 py-2">
                                    <select name="lines[0][account_id]" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm account-select" required>
                                        <option value="">Select Account</option>
                                        @foreach($accounts as $account)
                                            <option value="{{ $account->id }}">{{ $account->code }} - {{ $account->name }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td class="px-4 py-2">
                                    <input type="number" step="0.01" name="lines[0][debit]" value="0" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm debit-input" oninput="calculateTotal()">
                                </td>
                                <td class="px-4 py-2">
                                    <input type="number" step="0.01" name="lines[0][credit]" value="0" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm credit-input" oninput="calculateTotal()">
                                </td>
                                <td class="px-4 py-2">
                                    <input type="text" name="lines[0][description]" placeholder="Line desc" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                                </td>
                                <td class="px-4 py-2 text-center">
                                    <button type="button" onclick="removeLine(this)" class="text-red-500 hover:text-red-700">
                                        <i class="fa-solid fa-trash-can"></i>
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                        <tfoot class="bg-gray-50 border-t border-gray-200">
                            <tr>
                                <td class="px-4 py-3 text-right font-semibold text-gray-700">Totals:</td>
                                <td class="px-4 py-3 text-left font-bold text-blue-600" id="totalDebit">0.00</td>
                                <td class="px-4 py-3 text-left font-bold text-green-600" id="totalCredit">0.00</td>
                                <td colspan="2"></td>
                            </tr>
                            <tr>
                                <td colspan="5" class="px-4 py-2 text-sm" id="validationMessage"></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            <div class="mt-6 flex justify-end space-x-3">
                <a href="{{ route('journal-entries.index') }}" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">Cancel</a>
                <button type="submit" class="gradient-bg text-white px-6 py-2 rounded-lg font-semibold hover:opacity-90">
                    <i class="fa-solid fa-save"></i> Post Entry
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    let lineIndex = 1;

    function addLine() {
        const tbody = document.getElementById('linesBody');
        const tr = document.createElement('tr');
        tr.className = 'line-row';
        tr.innerHTML = `
            <td class="px-4 py-2">
                <select name="lines[${lineIndex}][account_id]" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm account-select" required>
                    <option value="">Select Account</option>
                    @foreach($accounts as $account)
                        <option value="{{ $account->id }}">{{ $account->code }} - {{ $account->name }}</option>
                    @endforeach
                </select>
            </td>
            <td class="px-4 py-2">
                <input type="number" step="0.01" name="lines[${lineIndex}][debit]" value="0" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm debit-input" oninput="calculateTotal()">
            </td>
            <td class="px-4 py-2">
                <input type="number" step="0.01" name="lines[${lineIndex}][credit]" value="0" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm credit-input" oninput="calculateTotal()">
            </td>
            <td class="px-4 py-2">
                <input type="text" name="lines[${lineIndex}][description]" placeholder="Line desc" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
            </td>
            <td class="px-4 py-2 text-center">
                <button type="button" onclick="removeLine(this)" class="text-red-500 hover:text-red-700">
                    <i class="fa-solid fa-trash-can"></i>
                </button>
            </td>
        `;
        tbody.appendChild(tr);
        lineIndex++;
    }

    function removeLine(btn) {
        const row = btn.closest('.line-row');
        if (document.querySelectorAll('.line-row').length <= 1) {
            alert('You must have at least one line.');
            return;
        }
        row.remove();
        calculateTotal();
    }

    function calculateTotal() {
        let totalDebit = 0, totalCredit = 0;
        document.querySelectorAll('.line-row').forEach(row => {
            const debit = parseFloat(row.querySelector('.debit-input')?.value || 0);
            const credit = parseFloat(row.querySelector('.credit-input')?.value || 0);
            totalDebit += debit;
            totalCredit += credit;
        });

        document.getElementById('totalDebit').innerText = totalDebit.toFixed(2);
        document.getElementById('totalCredit').innerText = totalCredit.toFixed(2);

        const msg = document.getElementById('validationMessage');
        if (totalDebit !== totalCredit) {
            msg.innerHTML = '<span class="text-red-500 font-semibold">⚠️ Total Debit does not equal Total Credit. Difference: ' + Math.abs(totalDebit - totalCredit).toFixed(2) + '</span>';
        } else {
            msg.innerHTML = '<span class="text-green-500 font-semibold">✅ Debit = Credit. Ready to post.</span>';
        }
    }

    // Initial validation
    window.onload = calculateTotal;
</script>
@endsection
