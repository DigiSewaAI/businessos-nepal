@extends('layouts.app')

@section('title', 'POS - BusinessOS Nepal')

@section('content')
<div class="pt-24 pb-12 px-4 bg-gray-100 min-h-screen">
    <div class="max-w-7xl mx-auto" x-data="posApp()" x-init="init()">
        <div class="grid lg:grid-cols-3 gap-6">
            <!-- Left: Products Grid (2/3) -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-4">
                    <div class="flex gap-4 mb-4">
                        <input type="text" x-model="search" @input="filterProducts()" placeholder="Search products by name or SKU..." class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <select x-model="categoryFilter" @change="filterProducts()" class="px-4 py-2 border border-gray-300 rounded-lg">
                            <option value="">All Categories</option>
                            <template x-for="category in categories" :key="category.id">
                                <option :value="category.id" x-text="category.name"></option>
                            </template>
                        </select>
                    </div>
                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3 max-h-[500px] overflow-y-auto">
                        <template x-for="product in filteredProducts" :key="product.id">
                            <div @click="addToCart(product)" 
                                 class="bg-gray-50 p-3 rounded-xl border border-gray-200 hover:border-blue-400 hover:shadow-md cursor-pointer transition-all text-center"
                                 :class="{'opacity-50 cursor-not-allowed': product.current_stock <= 0}">
                                <div class="w-full h-20 bg-gray-200 rounded-lg flex items-center justify-center text-gray-400 text-xs">
                                    <i class="fa-solid fa-box text-2xl"></i>
                                </div>
                                <p class="text-sm font-semibold text-gray-800 mt-2 truncate" x-text="product.name"></p>
                                <p class="text-xs text-gray-500" x-text="product.sku"></p>
                                <p class="text-sm font-bold text-blue-600">Rs. <span x-text="parseFloat(product.sale_price).toFixed(2)"></span></p>
                                <p class="text-xs font-semibold mt-1" 
                                   :class="product.current_stock > 0 ? 'text-green-600' : 'text-red-600'">
                                    <span x-text="product.current_stock > 0 ? product.current_stock + ' in stock' : 'Out of Stock'"></span>
                                </p>
                            </div>
                        </template>
                        <template x-if="filteredProducts.length === 0">
                            <div class="col-span-full text-center py-8 text-gray-400">
                                <i class="fa-solid fa-search text-4xl mb-2 block"></i>
                                No products found
                            </div>
                        </template>
                    </div>
                </div>
            </div>

            <!-- Right: Cart (1/3) -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-4 h-full flex flex-col">
                    <h3 class="font-bold text-lg text-gray-800 flex items-center gap-2">
                        <i class="fa-solid fa-cart-shopping text-blue-600"></i> Cart
                        <span class="text-sm font-normal text-gray-400 ml-auto" x-text="cart.length + ' items'"></span>
                    </h3>

                    <!-- Cart Items -->
                    <div class="flex-1 max-h-[350px] overflow-y-auto mt-4 space-y-2">
                        <template x-for="(item, index) in cart" :key="index">
                            <div class="flex items-center gap-2 bg-gray-50 p-2 rounded-lg border border-gray-100">
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-semibold text-gray-800 truncate" x-text="item.name"></p>
                                    <p class="text-xs text-gray-500">Rs. <span x-text="parseFloat(item.price).toFixed(2)"></span> x <span x-text="item.quantity"></span></p>
                                </div>
                                <div class="flex items-center gap-1">
                                    <button @click="updateQuantity(index, -1)" class="w-6 h-6 rounded bg-gray-200 hover:bg-gray-300 text-xs font-bold">-</button>
                                    <span class="w-6 text-center text-sm font-semibold" x-text="item.quantity"></span>
                                    <button @click="updateQuantity(index, 1)" class="w-6 h-6 rounded bg-gray-200 hover:bg-gray-300 text-xs font-bold">+</button>
                                </div>
                                <button @click="removeFromCart(index)" class="text-red-500 hover:text-red-700 text-sm ml-1">
                                    <i class="fa-solid fa-trash-can"></i>
                                </button>
                            </div>
                        </template>
                        <template x-if="cart.length === 0">
                            <div class="text-center py-8 text-gray-400 text-sm">
                                <i class="fa-solid fa-empty-set text-3xl mb-2 block"></i>
                                Cart is empty
                            </div>
                        </template>
                    </div>

                    <!-- Cart Summary -->
                    <div class="border-t border-gray-200 mt-4 pt-4 space-y-2">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500">Subtotal</span>
                            <span class="font-semibold">Rs. <span x-text="parseFloat(subtotal).toFixed(2)"></span></span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500">Discount</span>
                            <span class="font-semibold text-green-600">- Rs. <span x-text="parseFloat(discount).toFixed(2)"></span></span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500">Tax (13%)</span>
                            <span class="font-semibold">+ Rs. <span x-text="parseFloat(tax).toFixed(2)"></span></span>
                        </div>
                        <div class="flex justify-between text-lg font-bold border-t border-gray-200 pt-2">
                            <span>Total</span>
                            <span class="text-blue-600">Rs. <span x-text="parseFloat(total).toFixed(2)"></span></span>
                        </div>

                        <!-- Customer & Payment -->
                        <div class="space-y-2 mt-2">
                            <input type="text" x-model="customerPhone" placeholder="Customer Phone (optional)" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                            <input type="text" x-model="customerName" placeholder="Customer Name" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                            <select x-model="paymentMethod" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                                <option value="cash">Cash</option>
                                <option value="card">Card</option>
                                <option value="mobile">Mobile</option>
                                <option value="bank">Bank</option>
                            </select>
                            <input type="number" x-model="paidAmount" placeholder="Amount Received" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                            <div class="text-xs text-gray-500 text-right" x-show="paidAmount > 0">
                                Change: Rs. <span x-text="parseFloat(paidAmount - total).toFixed(2)"></span>
                            </div>
                        </div>

                        <button @click="completeSale()" class="w-full gradient-bg text-white py-3 rounded-xl font-bold hover:shadow-lg transition-all" :disabled="cart.length === 0">
                            <i class="fa-solid fa-check"></i> Complete Sale
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function posApp() {
    return {
        products: @json($products),
        customers: @json($customers),
        warehouses: @json($warehouses),
        categories: @json($categories),
        search: '',
        categoryFilter: '',
        filteredProducts: [],
        cart: [],
        customerPhone: '',
        customerName: '',
        paymentMethod: 'cash',
        paidAmount: 0,
        discount: 0,
        taxPercent: 13,

        init() {
            this.filteredProducts = this.products;
        },

        filterProducts() {
            let filtered = this.products;
            
            // Search filter
            if (this.search) {
                filtered = filtered.filter(p => 
                    p.name.toLowerCase().includes(this.search.toLowerCase()) ||
                    p.sku.toLowerCase().includes(this.search.toLowerCase())
                );
            }
            
            // Category filter
            if (this.categoryFilter) {
                filtered = filtered.filter(p => p.category_id == this.categoryFilter);
            }
            
            this.filteredProducts = filtered;
        },

        addToCart(product) {
            if (product.current_stock <= 0) {
                alert('❌ ' + product.name + ' is out of stock!');
                return;
            }

            const existing = this.cart.find(item => item.product_id === product.id);
            if (existing) {
                if (existing.quantity + 1 > product.current_stock) {
                    alert('❌ Only ' + product.current_stock + ' units available in stock!');
                    return;
                }
                existing.quantity += 1;
            } else {
                this.cart.push({
                    product_id: product.id,
                    name: product.name,
                    sku: product.sku,
                    price: parseFloat(product.sale_price),
                    quantity: 1,
                    max_stock: product.current_stock,
                    warehouse_id: this.warehouses.length > 0 ? this.warehouses[0].id : null,
                });
            }
        },

        updateQuantity(index, delta) {
            const item = this.cart[index];
            const newQty = item.quantity + delta;
            
            if (newQty < 1) {
                this.cart.splice(index, 1);
                return;
            }
            
            if (newQty > item.max_stock) {
                alert('❌ Only ' + item.max_stock + ' units available in stock!');
                return;
            }
            
            item.quantity = newQty;
        },

        removeFromCart(index) {
            this.cart.splice(index, 1);
        },

        get subtotal() {
            return this.cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
        },

        get tax() {
            return this.subtotal * (this.taxPercent / 100);
        },

        get total() {
            return this.subtotal - this.discount + this.tax;
        },

        completeSale() {
            if (this.cart.length === 0) return;

            const items = this.cart.map(item => ({
                product_id: item.product_id,
                quantity: item.quantity,
                price: item.price,
                warehouse_id: item.warehouse_id || (this.warehouses.length > 0 ? this.warehouses[0].id : null),
                total: item.price * item.quantity,
                discount: 0,
                tax: 0,
            }));

            const payload = {
                branch_id: {{ auth()->user()->branch_id ?? 'null' }},
                warehouse_id: this.warehouses.length > 0 ? this.warehouses[0].id : null,
                customer_name: this.customerName,
                customer_phone: this.customerPhone,
                discount: this.discount,
                discount_percent: 0,
                tax: this.tax,
                tax_percent: this.taxPercent,
                paid_amount: parseFloat(this.paidAmount) || this.total,
                payment_method: this.paymentMethod,
                items: items,
            };

            fetch('{{ route('sales.store') }}', {
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
                    alert('✅ Sale completed! Invoice: ' + data.invoice_no);
                    this.cart = [];
                    this.customerPhone = '';
                    this.customerName = '';
                    this.paidAmount = 0;
                    this.discount = 0;
                } else {
                    alert('❌ Error: ' + data.message);
                }
            })
            .catch(err => {
                alert('❌ An error occurred: ' + err.message);
            });
        }
    };
}
</script>
@endsection