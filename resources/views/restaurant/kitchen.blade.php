 @extends('layouts.admin')

@section('title', 'Kitchen')

@section('content')
<div class="pt-4 pb-4 px-4 bg-gray-50 min-h-screen">
    <div class="max-w-7xl mx-auto">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-900">Kitchen Dashboard</h1>
            <div class="flex gap-3">
                <a href="{{ route('restaurant.kot.index') }}" class="text-gray-600 hover:text-gray-800 text-sm">
                    <i class="fa-solid fa-list"></i> All KOTs
                </a>
                <a href="{{ route('restaurant.orders.active') }}" class="bg-orange-500 text-white px-4 py-2 rounded-lg text-sm font-semibold">
                    <i class="fa-solid fa-clock"></i> Active Orders
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @forelse($kots as $kot)
                <div class="bg-white rounded-2xl shadow-sm border border-yellow-200 p-4 animate-pulse">
                    <div class="flex justify-between items-start">
                        <div>
                            <div class="font-bold text-lg text-yellow-700">{{ $kot->kot_number }}</div>
                            <div class="text-sm text-gray-500">Table: {{ $kot->order->table->number ?? 'N/A' }}</div>
                            <div class="text-sm text-gray-500">Order: {{ $kot->order->order_number ?? 'N/A' }}</div>
                        </div>
                        <span class="px-2 py-1 bg-yellow-200 text-yellow-700 rounded-full text-xs font-bold">NEW</span>
                    </div>

                    <div class="mt-3 border-t border-gray-200 pt-3">
                        <ul>
                            @foreach($kot->items ?? [] as $item)
                                <li class="py-1 flex justify-between">
                                    <span>{{ $item['quantity'] }} × {{ $item['product'] }}</span>
                                    @if(isset($item['special_instructions']))
                                        <span class="text-xs text-orange-600">({{ $item['special_instructions'] }})</span>
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                    </div>

                    <div class="mt-4 flex gap-2">
                        <form action="{{ route('restaurant.kot.print', $kot) }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-blue-700">
                                <i class="fa-solid fa-print"></i> Print KOT
                            </button>
                        </form>
                        <form action="{{ route('restaurant.orders.status', $kot->order) }}" method="POST" class="inline">
                            @csrf
                            <input type="hidden" name="status" value="preparing">
                            <button type="submit" class="bg-yellow-500 text-white px-4 py-2 rounded-lg text-sm hover:bg-yellow-600">
                                <i class="fa-solid fa-fire"></i> Start
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="col-span-3 text-center py-12 text-gray-400">🎉 No pending KOTs. Kitchen is clear!</div>
            @endforelse
        </div>
    </div>
</div>
@endsection
