@extends('layouts.admin')

@section('title', 'Platform Settings')

@section('content')
<div class="container-fluid">
    <h1 class="mb-4">Platform Settings</h1>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.settings.update') }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="app_name" class="form-label">Application Name</label>
                        <input type="text" class="form-control" id="app_name" name="app_name" value="{{ $settings['app_name'] ?? 'BusinessOS' }}">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="app_timezone" class="form-label">Timezone</label>
                        <select class="form-select" id="app_timezone" name="app_timezone">
                            <option value="Asia/Kathmandu" {{ ($settings['app_timezone'] ?? '') == 'Asia/Kathmandu' ? 'selected' : '' }}>Asia/Kathmandu</option>
                            <option value="UTC" {{ ($settings['app_timezone'] ?? '') == 'UTC' ? 'selected' : '' }}>UTC</option>
                            <option value="Asia/Kolkata" {{ ($settings['app_timezone'] ?? '') == 'Asia/Kolkata' ? 'selected' : '' }}>Asia/Kolkata</option>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="app_currency" class="form-label">Currency</label>
                        <input type="text" class="form-control" id="app_currency" name="app_currency" value="{{ $settings['app_currency'] ?? 'NPR' }}">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="app_language" class="form-label">Language</label>
                        <select class="form-select" id="app_language" name="app_language">
                            <option value="en" {{ ($settings['app_language'] ?? '') == 'en' ? 'selected' : '' }}>English</option>
                            <option value="ne" {{ ($settings['app_language'] ?? '') == 'ne' ? 'selected' : '' }}>Nepali</option>
                        </select>
                    </div>
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary">Save Settings</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection