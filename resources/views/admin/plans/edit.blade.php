@extends('layouts.admin')

@section('title', 'Edit Plan')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Edit Plan: {{ $plan->name }}</h1>
        <a href="{{ route('admin.plans.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.plans.update', $plan->id) }}" method="POST">
                @csrf @method('PUT')
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="name" class="form-label">Plan Name *</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $plan->name) }}" required>
                        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="price" class="form-label">Price (NPR) *</label>
                        <input type="number" step="0.01" class="form-control @error('price') is-invalid @enderror" id="price" name="price" value="{{ old('price', $plan->price) }}" required>
                        @error('price') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="duration_months" class="form-label">Duration (Months) *</label>
                        <input type="number" class="form-control @error('duration_months') is-invalid @enderror" id="duration_months" name="duration_months" value="{{ old('duration_months', $plan->duration_months) }}" min="1" max="60" required>
                        @error('duration_months') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="is_active" class="form-label">Status</label>
                        <select class="form-select" id="is_active" name="is_active">
                            <option value="1" {{ old('is_active', $plan->is_active) == 1 ? 'selected' : '' }}>Active</option>
                            <option value="0" {{ old('is_active', $plan->is_active) == 0 ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="max_users" class="form-label">Max Users *</label>
                        <input type="number" class="form-control @error('max_users') is-invalid @enderror" id="max_users" name="max_users" value="{{ old('max_users', $plan->max_users) }}" min="1" required>
                        @error('max_users') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="max_products" class="form-label">Max Products *</label>
                        <input type="number" class="form-control @error('max_products') is-invalid @enderror" id="max_products" name="max_products" value="{{ old('max_products', $plan->max_products) }}" min="0" required>
                        @error('max_products') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="max_branches" class="form-label">Max Branches *</label>
                        <input type="number" class="form-control @error('max_branches') is-invalid @enderror" id="max_branches" name="max_branches" value="{{ old('max_branches', $plan->max_branches) }}" min="1" required>
                        @error('max_branches') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-12 mb-3">
                        <label for="features" class="form-label">Features (JSON)</label>
                        <textarea class="form-control @error('features') is-invalid @enderror" id="features" name="features" rows="3">{{ old('features', json_encode($plan->features)) }}</textarea>
                        @error('features') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        <small class="text-muted">Enter valid JSON array or object.</small>
                    </div>
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary">Update Plan</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection