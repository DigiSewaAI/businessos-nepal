@extends('layouts.app')

@section('title', 'Changelog - BusinessOS Nepal')

@section('content')
<div class="pt-32 pb-20 px-4 bg-gray-50 min-h-screen">
    <div class="max-w-4xl mx-auto">
        <h1 class="text-4xl font-bold text-gray-900 mb-8">Changelog</h1>
        <p class="text-gray-500 mb-12">All notable changes to BusinessOS Nepal.</p>

        <div class="space-y-12">
            <!-- V1.0.0 -->
            <div>
                <div class="flex items-center gap-4 mb-4">
                    <h2 class="text-2xl font-bold text-gray-800">v1.0.0</h2>
                    <span class="text-sm text-gray-400">— March 31, 2027</span>
                    <span class="px-3 py-1 rounded-full bg-green-100 text-green-700 text-xs font-semibold">Latest</span>
                </div>
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 space-y-2">
                    <p class="font-semibold text-gray-800">🎉 Initial Launch — "Foundation Release"</p>
                    <ul class="list-disc list-inside space-y-1 text-sm text-gray-600">
                        <li>Authentication, Multi-tenancy, Organizations & Branches</li>
                        <li>User Management with Roles (Owner, Manager, Cashier, Inventory)</li>
                        <li>Granular Permissions (Spatie/laravel-permission)</li>
                        <li>Product Management (Variants, SKU, Barcode, Images)</li>
                        <li>Categories (Unlimited Hierarchy) & Brands</li>
                        <li>Units (PCS, KG, BOX, LTR) & Multi-Warehouse Support</li>
                        <li>Inventory Management (Stock, Transfers, Adjustments)</li>
                        <li>Sales & POS (Invoices, Discounts, Taxes, Receipts)</li>
                        <li>Purchase Management (Suppliers, POs, Returns)</li>
                        <li>Expense Tracking & Cashbook</li>
                        <li>Basic Accounting (Ledger-Ready)</li>
                        <li>20+ Operational Reports</li>
                        <li>Subscription Engine (Plans, Usage Limits)</li>
                        <li>Audit Logs</li>
                        <li>Nepali (नेपाली) & English Language Support</li>
                        <li>NPR Currency Formatting</li>
                    </ul>
                </div>
            </div>

            <!-- Beta -->
            <div>
                <div class="flex items-center gap-4 mb-4">
                    <h2 class="text-2xl font-bold text-gray-800">v1.0.0-beta.1</h2>
                    <span class="text-sm text-gray-400">— February 15, 2027</span>
                    <span class="px-3 py-1 rounded-full bg-yellow-100 text-yellow-700 text-xs font-semibold">Beta</span>
                </div>
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
                    <ul class="list-disc list-inside space-y-1 text-sm text-gray-600">
                        <li>Complete Phase 0 to Phase 6 implementation</li>
                        <li>Manual subscription toggle for beta testing</li>
                    </ul>
                </div>
            </div>

            <!-- Alpha -->
            <div>
                <div class="flex items-center gap-4 mb-4">
                    <h2 class="text-2xl font-bold text-gray-800">v0.5.0</h2>
                    <span class="text-sm text-gray-400">— December 31, 2026</span>
                    <span class="px-3 py-1 rounded-full bg-purple-100 text-purple-700 text-xs font-semibold">Alpha</span>
                </div>
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
                    <ul class="list-disc list-inside space-y-1 text-sm text-gray-600">
                        <li>Phase 4: Expenses, Cashbook, Basic Accounting</li>
                        <li>Phase 5: Reports (draft)</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection