@extends('layouts.app')

@section('title', 'New Purchase - BusinessOS Nepal')

@section('content')
<div class="pt-24 pb-12 px-4 bg-gray-50 min-h-screen">
    <div class="max-w-4xl mx-auto" x-data="purchaseForm()">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-8">
            <h1 class="text-2xl font-bold text-gray-900 mb-6">New Purchase Order</h1>

            <form @submit.prevent="submitPurchase()">
                @csrf

                <div class="grid grid-cols-2 gap-4 mb-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Supplier *</label>
                        <select x-model="form.supplier_id" required class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                            <option value="">Select Supplier</option>
                            @foreach($suppliers as $supplier)
                            <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Branch *</label>
                        <select x-model="form.branch_id" required class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                            <option value="">Select Branch</option>
                            @foreach(auth()->user()->organization->branches as $branch)
                            <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Supplier Invoice #</label>
                        <input type="text" x-model="form.invoice_no" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Expected Date</label>
                        <input type="date" x-model="form.expected_date" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                    </div>
                </div>

                <!-- Products Selection -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Add Products</label>
                    <div class="flex gap-2">
                        <select x-model="selectedProduct" class="flex-1 px-3 py-2 border border-gray-300 rounded-lg">
                            <option value="">Select Product</option>
                            @foreach($products as $product)
                            <option value="{{ $product->id }}">{{ $product->name }} ({{ $product->sku }}) - Rs. {{ number_format($product->purchase_price, 2) }}</option>
                            @endforeach
                        </select>
                        <input type="number" x-model="selectedQty" placeholder="Qty" class="w-20 px-3 py-2 border border-gray-300 rounded-lg">
                        <input type="number" x-model="selectedPrice" placeholder="Price" class="w-28 px-3 py-2 border border-gray-300 rounded-lg">
                        <button type="button" @click="addProduct()" class="gradient-bg text-white px-4 py-2 rounded-lg text-sm font-semibold">
                            Add
                        </button>
                    </div>
                </div>

                <!-- Purchase Items -->
                <div class="mb-6">
                    <table class="w-full">
                        <thead class="border-b border-gray-200">
                            <tr>
                                <th class="py-2 text-left text-sm font-semibold text-gray-600">Product</th>
                                <th class="py-2 text-center text-sm font-semibold text-gray-600">Qty</th>
                                <th class="py-2 text-right text-sm font-semibold text-gray-600">Price</th>
                                <th class="py-2 text-right text-sm font-semibold text-gray-600">Total</th>
                                <th class="py-2 text-center text-sm font-semibold text-gray-600">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            <template x-for="(item, index) in items" :key="index">
                                <tr>
                                    <td class="py-2 text-sm text-gray-800" x-text="item.name"></td>
                                    <td class="py-2 text-sm text-center" x-text="item.quantity"></td>
                                    <td class="py-2 text-sm text-right">Rs. <span x-text="parseFloat(item.purchase_price).toFixed(2)"></span></td>
                                    <td class="py-2 text-sm font-semibold text-right">Rs. <span x-text="parseFloat(item.total).toFixed(2)"></span></td>
                                    <td class="py-2 text-center">
                                        <button type="button" @click="removeItem(index)" class="text-red-500 hover:text-red-700">
                                            <i class="fa-solid fa-trash-can"></i>
                                        </button>
                                    </td>
                                </tr>
                            </template>
                            <template x-if="items.length === 0">
                                <tr>
                                    <td colspan="5" class="py-4 text-center text-gray-400 text-sm">No products added yet</td>
                                </tr>
                            </template>
                        </tbody>
                        <tfoot class="border-t-2 border-gray-200">
                            <tr>
                                <td colspan="3" class="py-2 text-right font-medium">Subtotal</td>
                                <td class="py-2 text-right font-medium">Rs. <span x-text="parseFloat(subtotal).toFixed(2)"></span></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td colspan="3" class="py-2 text-right">
                                    <input type="number" x-model="form.discount" placeholder="Discount" class="w-32 px-2 py-1 border border-gray-300 rounded text-sm text-right">
                                </td>
                                <td class="py-2 text-right text-green-600">- Rs. <span x-text="parseFloat(form.discount || 0).toFixed(2)"></span></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td colspan="3" class="py-2 text-right">
                                    <input type="number" x-model="form.tax" placeholder="Tax" class="w-32 px-2 py-1 border border-gray-300 rounded text-sm text-right">
                                </td>
                                <td class="py-2 text-right text-gray-600">+ Rs. <span x-text="parseFloat(form.tax || 0).toFixed(2)"></span></td>
                                <td></td>
                            </tr>
                            <tr class="border-t border-gray-200">
                                <td colspan="3" class="py-2 text-right font-bold text-lg">Total</td>
                                <td class="py-2 text-right font-bold text-lg text-blue-600">Rs. <span x-text="parseFloat(total).toFixed(2)"></span></td>
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <div class="flex justify-end">
                    <a href="{{ route('purchases.index') }}" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 mr-3">Cancel</a>
                    <button type="submit" class="gradient-bg text-white px-6 py-2 rounded-lg font-semibold hover:shadow-lg transition-all">
                        Create Purchase Order
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function purchaseForm() {
    return {
        form: {
            supplier_id: '',
            branch_id: '',
            invoice_no: '',
            expected_date: '',
            discount: 0,
            tax: 0,
        },
        items: [],
        selectedProduct: '',
        selectedQty: 1,
        selectedPrice: 0,

        get subtotal() {
            return this.items.reduce((sum, item) => sum + (item.purchase_price * item.quantity), 0);
        },

        get total() {
            return this.subtotal - (parseFloat(this.form.discount) || 0) + (parseFloat(this.form.tax) || 0);
        },

        addProduct() {
            if (!this.selectedProduct) return;
            const product = @json($products).find(p => p.id == this.selectedProduct);
            if (!product) return;

            this.items.push({
                product_id: product.id,
                name: product.name,
                quantity: parseInt(this.selectedQty) || 1,
                purchase_price: parseFloat(this.selectedPrice) || product.purchase_price,
                total: (parseFloat(this.selectedPrice) || product.purchase_price) * (parseInt(this.selectedQty) || 1),
                warehouse_id: {{ $warehouses->first()->id ?? 0 }},
                discount: 0,
                tax: 0,
            });

            this.selectedProduct = '';
            this.selectedQty = 1;
            this.selectedPrice = 0;
        },

        removeItem(index) {
            this.items.splice(index, 1);
        },

        submitPurchase() {
            if (this.items.length === 0) {
                alert('Please add at least one product.');
                return;
            }

            const payload = {
                ...this.form,
                items: this.items.map(item => ({
                    product_id: item.product_id,
                    product_variant_id: null,
                    warehouse_id: item.warehouse_id,
                    quantity: item.quantity,
                    purchase_price: item.purchase_price,
                    discount: 0,
                    tax: 0,
                    total: item.total,
                })),
            };

            fetch('{{ route('purchases.store') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                },
                body: JSON.stringify(payload),
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    window.location.href = data.redirect;
                } else {
                    alert('Error: ' + data.message);
                }
            })
            .catch(err => alert('An error occurred: ' + err.message));
        }
    };
}
</script>
@endsection