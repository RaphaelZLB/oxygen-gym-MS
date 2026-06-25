@extends('layouts.app')

@section('title', 'Plan Details')

@section('content')
    <div class="d-flex align-items-center justify-content-between app-page-header gap-3 flex-wrap">
        <div>
            <h1 class="h3 app-page-title">{{ $plan->name }}</h1>
            <div class="text-muted small">
                {{ $plan->duration_days }} days • ${{ number_format((float) $plan->price, 2) }}
                @if (($plan->plan_kind ?? 'individual') === 'couple')
                    • <span class="badge text-bg-warning text-dark">Couple plan</span>
                @else
                    • Individual
                @endif
            </div>
        </div>

        <div class="d-flex gap-2">
            <a class="btn btn-outline-secondary" href="{{ route('plans.index') }}">Back</a>
            @can('manage-subscriptions')
                <a class="btn btn-primary" href="{{ route('plans.edit', $plan) }}">Edit</a>
            @endcan
        </div>
    </div>

    <div class="card app-card app-card-table">
        <div class="card-header">Subscriptions</div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped mb-0">
                    <thead>
                    <tr>
                        <th>Member</th>
                        <th>Start date</th>
                        <th class="text-end">End date</th>
                        <th class="text-end">Status</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse ($subscriptions as $sub)
                        <tr>
                            <td>{{ $sub->member?->first_name }} {{ $sub->member?->last_name }}</td>
                            <td>{{ $sub->start_date?->toDateString() }}</td>
                            <td class="text-end">{{ $sub->end_date?->toDateString() }}</td>
                            <td class="text-end">
                                @if ($sub->status === 'active')
                                    <span class="badge bg-success">active</span>
                                @else
                                    <span class="badge bg-secondary">expired</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted p-3">No subscriptions found.</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

