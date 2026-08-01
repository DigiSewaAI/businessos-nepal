@extends('layouts.admin')

@section('title', 'Analytics')

@section('content')
<div class="container-fluid">
    <h1 class="mb-4">Platform Analytics</h1>

    <div class="row">
        <div class="col-md-3">
            <div class="card bg-primary text-white mb-3">
                <div class="card-body">
                    <h5 class="card-title">Total Organizations</h5>
                    <h2>{{ $totalOrganizations ?? 0 }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white mb-3">
                <div class="card-body">
                    <h5 class="card-title">Total Users</h5>
                    <h2>{{ $totalUsers ?? 0 }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white mb-3">
                <div class="card-body">
                    <h5 class="card-title">Total Revenue</h5>
                    <h2>NPR {{ number_format($totalRevenue ?? 0, 2) }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-white mb-3">
                <div class="card-body">
                    <h5 class="card-title">Total Sales</h5>
                    <h2>{{ $totalSales ?? 0 }}</h2>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header"><strong>Organizations by Plan</strong></div>
                <div class="card-body">
                    @if(isset($organizationsByPlan) && $organizationsByPlan->count())
                        <ul class="list-group">
                            @foreach($organizationsByPlan as $plan => $count)
                                <li class="list-group-item d-flex justify-content-between">
                                    <span>{{ $plan ?: 'No Plan' }}</span>
                                    <span class="badge bg-primary">{{ $count }}</span>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-muted">No data available.</p>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header"><strong>Monthly Registrations</strong></div>
                <div class="card-body">
                    @if(isset($monthlyRegistrations) && $monthlyRegistrations->count())
                        <canvas id="registrationsChart" height="200"></canvas>
                    @else
                        <p class="text-muted">No registration data available.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    @if(isset($monthlyRegistrations) && $monthlyRegistrations->count())
    const ctx = document.getElementById('registrationsChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: @json($monthlyRegistrations->pluck('month')),
            datasets: [{
                label: 'New Organizations',
                data: @json($monthlyRegistrations->pluck('count')),
                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 2,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { position: 'bottom' }
            }
        }
    });
    @endif
</script>
@endpush
@endsection