@extends('layouts.app')

@section('title', 'Plans')

@section('content')
    <div class="d-flex align-items-center justify-content-between app-page-header">
        <h1 class="h3 app-page-title">Plans</h1>
        <a class="btn btn-primary" href="{{ route('plans.create') }}">Create</a>
    </div>

    <div class="card app-card app-card-table">
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                <tr>
                    <th>Name</th>
                    <th>Type</th>
                    <th class="text-end">Duration (days)</th>
                    <th class="text-end">Price</th>
                    <th class="text-end">Actions</th>
                </tr>
                </thead>
                <tbody>
                @forelse($plans as $plan)
                    <tr>
                        <td>{{ $plan->name }}</td>
                        <td>
                            @if (($plan->plan_kind ?? 'individual') === 'couple')
                                <span class="badge text-bg-warning text-dark">Couple</span>
                            @else
                                <span class="text-muted small">Individual</span>
                            @endif
                        </td>
                        <td class="text-end">{{ $plan->duration_days }}</td>
                        <td class="text-end">${{ number_format((float)$plan->price, 2) }}</td>
                        <td class="text-end">
                            <a class="btn btn-sm btn-outline-primary" href="{{ route('plans.show', $plan) }}">View</a>
                            <a class="btn btn-sm btn-outline-primary" href="{{ route('plans.edit', $plan) }}">Edit</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted p-3">No plans found.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3">
        {{ $plans->withQueryString()->links('pagination::bootstrap-5') }}
    </div>
@endsection

