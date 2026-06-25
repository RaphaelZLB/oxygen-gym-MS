@extends('layouts.app')

@section('title', 'Edit Plan')

@section('content')
    <div class="app-page-header">
        <h1 class="h3 app-page-title">Edit Plan</h1>
    </div>

    <div class="card app-card">
        <div class="card-body">
            <form method="POST" action="{{ route('plans.update', $plan) }}">
                @csrf
                @method('PUT')

                <div class="row g-3">
                    <div class="col-12 col-md-6">
                        <label class="form-label">Name</label>
                        <input class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name', $plan->name) }}" required>
                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-12 col-md-3">
                        <label class="form-label">Duration days</label>
                        <input type="number" min="0.5" class="form-control @error('duration_days') is-invalid @enderror" name="duration_days" value="{{ old('duration_days', $plan->duration_days) }}" required>
                        @error('duration_days')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-12 col-md-3">
                        <label class="form-label">Price</label>
                        <input type="number" step="0.01" min="0" class="form-control @error('price') is-invalid @enderror" name="price" value="{{ old('price', $plan->price) }}" required>
                        @error('price')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label">Plan type</label>
                        <select class="form-select @error('plan_kind') is-invalid @enderror" name="plan_kind" required>
                            <option value="individual" @selected(old('plan_kind', $plan->plan_kind ?? 'individual') === 'individual')>Individual</option>
                            <option value="couple" @selected(old('plan_kind', $plan->plan_kind ?? 'individual') === 'couple')>Couple</option>
                        </select>
                        @error('plan_kind')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="mt-3 d-flex gap-2">
                    <button class="btn btn-primary" type="submit">Update</button>
                    <a class="btn btn-outline-secondary" href="{{ route('plans.index') }}">Cancel</a>
                </div>
            </form>
        </div>
    </div>
@endsection

