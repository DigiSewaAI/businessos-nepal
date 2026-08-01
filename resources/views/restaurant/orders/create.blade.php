@extends('layouts.admin)

@section('title', 'Create Order')

@section('content')
<div class="pt-4 pb-4 px-4 bg-gray-50 min-h-screen">
    <div class="max-w-6xl mx-auto">
        <div class="flex items-center mb-6">
            <a href="{{ route('restaurant.orders.active') }}" class="text-gray-500 hover:text-gray-700 mr-4">
                <i class="fa-solid fa-arrow-left"></i>
            </a>
            <h1 class="text-2xl font-bold text-gray-900">New Order</h1>
        </div>

        <form action="{{ route('restaurant.orders.store') }}" method="POST">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Table <span class="text-red-500">*</span></label>
                    <select name="table_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg @error('table_id') border-red-500 @enderror" required>
                        <option value="">Select Table</option>
                        @foreach($tables as $table)
                            <option value="{{ $table->id }}" {{ request('table_id') == $table->id ? 'selected' : '' }}>
                                {{ $table->number }} ({{ $table->status }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Order Type</label>
                    <select name="order_type" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                        <option value="dine_in">Dine In</option>
                        <option value="takeaway">Takeaway</option>
                        <option value="delivery">Delivery</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Guest Count</label>
                    <input type="number" name="guest_count" value="{{ old('guest_count', 1) }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg" min="1">
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 bg-gray-50 border-b border-gray-200 flex justify-between items-center">
                    <h3 class="text-lg font-semibold text-gray-800">Items</h3>
                    <button type="button" onclick="addItem()" class="text-blue-600 hover:text-blue-800 text-sm">
                        <i class="fa-solid fa-plus"></i> Add Product
                    </button>
                </div>

                <div class="overflow-x-auto p-4" id="itemsContainer">
                    <table class="w-full" id="itemsTable">
                        <thead>
                            <tr>
                                <th class="text-left text-xs font-medium text-gray-500 uppercase">Product</th>
                                <th class="text-left text-xs font-medium text-gray-500 uppercase">Qty</th>
                                <th class="text-left text-xs font-medium text-gray-500 uppercase">Price</th>
                                <th class="text-left text-xs font-medium text-gray-500 uppercase">Total</th>
                                <th class="text-left text-xs font-medium text-gray-500 uppercase">Action</th>
                            </tr>
                        </thead>
                        <tbody id="itemsBody">
                            <tr class="item-row">
                                <td class="py-2">
                                    <select name="items[0][product_id]" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm product-select" required>
                                        <option value="">Select Product</option>
                                        @foreach($products as $product)
                                            <option value="{{ $product->id }}" data-price="{{ $product->selling_price ?? 0 }}">
                                                {{ $product->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </td>
                                <td class="py-2">
                                    <input type="number" name="items[0][quantity]" value="1" class="w-20 px-3 py-2 border border-gray-300 rounded-lg text-sm qty-input" min="1" onchange="calcItemTotal(this)">
                                </td>
                                <td class="py-2">
                                    <input type="number" step="0.01" name="items[0][price]" value="0" class="w-24 px-3 py-2 border border-gray-300 rounded-lg text-sm price-input" onchange="calcItemTotal(this)">
                                </td>
                                <td class="py-2">
                                    <span class="item-total font-semibold">0.00</span>
                                    <input type="hidden" name="items[0][total]" value="0" class="total-hidden">
                                </td>
                                <td class="py-2">
                                    <button type="button" onclick="removeItem(this)" class="text-red-500 hover:text-red-700">
                                        <i class="fa-solid fa-trash-can"></i>
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex justify-end space-x-4">
                    <div class="text-right">
                        <div class="text-sm text-gray-500">Subtotal: <span id="subtotalDisplay">0.00</span></div>
                        <div class="text-sm text-gray-500">Tax: <span id="taxDisplay">0.00</span></div>
                        <div class="text-xl font-bold">Total: <span id="totalDisplay">0.00</span></div>
                    </div>
                </div>
            </div>

            <div class="mt-6 flex justify-end space-x-3">
                <a href="{{ route('restaurant.orders.active') }}" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">Cancel</a>
                <button type="submit" class="gradient-bg text-white px-6 py-2 rounded-lg font-semibold hover:opacity-90">
                    <i class="fa-solid fa-check"></i> Place Order
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    let itemIndex = 1;

    function addItem() {
        const tbody = document.getElementById('itemsBody');
        const tr = document.createElement('tr');
        tr.className = 'item-row';
        tr.innerHTML = `
            <td class="py-2">
                <select name="items[${itemIndex}][product_id]" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm product-select" required>
                    <option value="">Select Product</option>
                    @foreach($products as $product)
                        <option value="{{ $product->id }}" data-price="{{ $product->selling_price ?? 0 }}">
                            {{ $product->name }}
                        </option>
                    @endforeach
                </select>
            </td>
            <td class="py-2">
                <input type="number" name="items[${itemIndex}][quantity]" value="1" class="w-20 px-3 py-2 border border-gray-300 rounded-lg text-sm qty-input" min="1" onchange="calcItemTotal(this)">
            </td>
            <td class="py-2">
                <input type="number" step="0.01" name="items[${itemIndex}][price]" value="0" class="w-24 px-3 py-2 border border-gray-300 rounded-lg text-sm price-input" onchange="calcItemTotal(this)">
            </td>
            <td class="py-2">
                <span class="item-total font-semibold">0.00</span>
                <input type="hidden" name="items[${itemIndex}][total]" value="0" class="total-hidden">
            </td>
            <td class="py-2">
                <button type="button" onclick="removeItem(this)" class="text-red-500 hover:text-red-700">
                    <i class="fa-solid fa-trash-can"></i>
                </button>
            </td>
        `;
        tbody.appendChild(tr);
        itemIndex++;
        updateTotals();
    }

    function removeItem(btn) {
        const row = btn.closest('.item-row');
        if (document.querySelectorAll('.item-row').length <= 1) {
            alert('At least one item required.');
            return;
        }
        row.remove();
        updateTotals();
    }

    function calcItemTotal(input) {
        const row = input.closest('.item-row');
        const qty = parseFloat(row.querySelector('.qty-input').value) || 0;
        const price = parseFloat(row.querySelector('.price-input').value) || 0;
        const total = qty * price;
        row.querySelector('.item-total').textContent = total.toFixed(2);
        row.querySelector('.total-hidden').value = total;
        updateTotals();
    }

    // Auto-set price when product selected
    document.addEventListener('change', function(e) {
        if (e.target.classList.contains('product-select')) {
            const row = e.target.closest('.item-row');
            const price = e.target.options[e.target.selectedIndex]?.dataset?.price || 0;
            row.querySelector('.price-input').value = price;
            calcItemTotal(row.querySelector('.price-input'));
        }
    });

    function updateTotals() {
        let subtotal = 0;
        document.querySelectorAll('.item-row').forEach(row => {
            const total = parseFloat(row.querySelector('.total-hidden').value) || 0;
            subtotal += total;
        });
        const tax = subtotal * 0.13; // 13% VAT
        const total = subtotal + tax;

        document.getElementById('subtotalDisplay').textContent = subtotal.toFixed(2);
        document.getElementById('taxDisplay').textContent = tax.toFixed(2);
        document.getElementById('totalDisplay').textContent = total.toFixed(2);
    }

    // Initial calculation
    window.onload = function() {
        document.querySelectorAll('.item-row').forEach(row => {
            const priceInput = row.querySelector('.price-input');
            const productSelect = row.querySelector('.product-select');
            if (productSelect && productSelect.options[productSelect.selectedIndex]?.dataset?.price) {
                priceInput.value = productSelect.options[productSelect.selectedIndex].dataset.price;
                calcItemTotal(priceInput);
            }
        });
        updateTotals();
    };
</script>
@endsection 
