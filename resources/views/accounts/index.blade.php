@extends('layouts.app')

@section('title', 'Chart of Accounts')

@section('content')
<div class="pt-24 pb-12 px-4 bg-gray-50 min-h-screen">
    <div class="max-w-7xl mx-auto">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-900">Chart of Accounts</h1>
            <a href="{{ route('accounts.create') }}" class="gradient-bg text-white px-4 py-2 rounded-lg text-sm font-semibold">
                <i class="fa-solid fa-plus"></i> New Account
            </a>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Code</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Balance</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($accounts as $account)
                        <tr>
                            <td class="px-6 py-4 text-sm font-mono text-gray-800">{{ $account->code }}</td>
                            <td class="px-6 py-4 text-sm text-gray-800">{{ $account->name }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ ucfirst($account->type) }}</td>
                            <td class="px-6 py-4 text-sm font-semibold 
                                @if($account->balance > 0) text-green-600 @else text-red-600 @endif">
                                {{ number_format($account->balance, 2) }}
                            </td>
                            <td class="px-6 py-4 text-sm">
                                <span class="px-2 py-1 rounded-full text-xs font-semibold 
                                    {{ $account->is_active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700' }}">
                                    {{ $account->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm">
                                <a href="{{ route('accounts.edit', $account) }}" class="text-blue-600 hover:text-blue-800 mr-2">
                                    <i class="fa-solid fa-edit"></i>
                                </a>
                                <form action="{{ route('accounts.destroy', $account) }}" method="POST" class="inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800" onclick="return confirm('Delete this account?')">
                                        <i class="fa-solid fa-trash-can"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @if($account->children->count())
                            @foreach($account->children as $child)
                                <tr>
                                    <td class="px-6 py-4 text-sm font-mono text-gray-600 pl-12">├─ {{ $child->code }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-700">{{ $child->name }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-500">{{ ucfirst($child->type) }}</td>
                                    <td class="px-6 py-4 text-sm font-semibold">{{ number_format($child->balance, 2) }}</td>
                                    <td class="px-6 py-4 text-sm">
                                        <span class="px-2 py-1 rounded-full text-xs font-semibold 
                                            {{ $child->is_active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700' }}">
                                            {{ $child->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-sm">
                                        <a href="{{ route('accounts.edit', $child) }}" class="text-blue-600 hover:text-blue-800 mr-2">
                                            <i class="fa-solid fa-edit"></i>
                                        </a>
                                        <form action="{{ route('accounts.destroy', $child) }}" method="POST" class="inline">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-800" onclick="return confirm('Delete this account?')">
                                                <i class="fa-solid fa-trash-can"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection