<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu - Table {{ $table->number }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body class="bg-gray-50">
    <div class="max-w-4xl mx-auto px-4 py-8">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 mb-6">
            <h1 class="text-2xl font-bold text-gray-900">Welcome to Table {{ $table->number }}</h1>
            <p class="text-gray-500">Scan the QR code to place your order.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @forelse($products as $product)
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-4 hover:shadow-md transition">
                    <div class="flex justify-between items-start">
                        <div>
                            <h3 class="font-bold text-lg">{{ $product->name }}</h3>
                            <p class="text-sm text-gray-500">{{ $product->category->name ?? 'General' }}</p>
                            <p class="text-lg font-bold text-blue-600 mt-2">Rs. {{ number_format($product->selling_price ?? 0, 2) }}</p>
                        </div>
                        <button onclick="addToCart({{ $product->id }}, '{{ $product->name }}', {{ $product->selling_price ?? 0 }})"
                            class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 text-sm">
                            <i class="fa-solid fa-plus"></i> Add
                        </button>
                    </div>
                </div>
            @empty
                <div class="col-span-2 text-center py-12 text-gray-400">No menu items available.</div>
            @endforelse
        </div>

        <!-- Cart -->
        <div class="fixed bottom-0 left-0 right-0 bg-white border-t border-gray-200 p-4 shadow-lg">
            <div class="max-w-4xl mx-auto flex justify-between items-center">
                <div>
                    <span class="font-bold">Items: <span id="cartCount">0</span></span>
                    <span class="ml-4 font-bold">Total: Rs. <span id="cartTotal">0.00</span></span>
                </div>
                <button onclick="placeOrder()" class="gradient-bg text-white px-6 py-2 rounded-lg font-semibold hover:opacity-90">
                    <i class="fa-solid fa-check"></i> Place Order
                </button>
            </div>
        </div>
    </div>

    <script>
        let cart = [];

        function addToCart(id, name, price) {
            const existing = cart.find(item => item.id === id);
            if (existing) {
                existing.quantity++;
            } else {
                cart.push({ id, name, price, quantity: 1 });
            }
            updateCart();
        }

        function updateCart() {
            let total = 0;
            let count = 0;
            cart.forEach(item => {
                total += item.price * item.quantity;
                count += item.quantity;
            });
            document.getElementById('cartCount').textContent = count;
            document.getElementById('cartTotal').textContent = total.toFixed(2);
        }

        function placeOrder() {
            if (cart.length === 0) {
                alert('Please add items to your cart.');
                return;
            }

            // In real implementation, send to server
            console.log('Order:', cart);
            alert('Order placed! The waiter will serve you shortly.');
            cart = [];
            updateCart();
        }
    </script>
</body>
</html> 
