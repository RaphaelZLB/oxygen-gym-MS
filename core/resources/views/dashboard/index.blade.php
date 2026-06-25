@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <div class="d-flex align-items-center justify-content-between app-page-header gap-3 flex-wrap">
        <div class="d-flex align-items-center gap-3">
            <h1 class="h3 app-page-title">Dashboard</h1>
        </div>
    </div>

    {{-- <div class="row g-3 mb-4">
        <div class="col-12 col-lg-8">
            @include('partials.gym-carousel')
        </div>
        <div class="col-12 col-lg-4">
            @include('partials.gym-info')
        </div>
    </div> --}}

    <div class="row g-3 mb-4">
        <div class="col-12 col-md-6 col-lg-3">
            <div class="card app-card">
                <div class="card-body">
                    <div class="text-muted small">Total Members</div>
                    <div class="h4 mb-0">{{ $stats['total_members'] }}</div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-6 col-lg-3">
            <div class="card app-card">
                <div class="card-body">
                    <div class="text-muted small">Active Subscriptions</div>
                    <div class="h4 mb-0">{{ $stats['active_subscriptions'] }}</div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-6 col-lg-3">
            <div class="card app-card">
                <div class="card-body">
                    <div class="text-muted small">Expired Subscriptions</div>
                    <div class="h4 mb-0">{{ $stats['expired_subscriptions'] }}</div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-6 col-lg-3">
            <div class="card app-card">
                <div class="card-body">
                    <div class="text-muted small">Revenue This Month</div>
                    <div class="h4 mb-0">${{ number_format($stats['revenue_this_month'], 2) }}</div>
                </div>
            </div>
        </div>
    </div>

    @can('manage-subscriptions')
        <div class="card app-card app-card-table">
            <div class="card-header">Expiring Soon (next 3 days)</div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                        <tr>
                            <th>Member</th>
                            <th>Phone</th>
                            <th>Plan</th>
                            <th>Plan type</th>
                            <th class="text-end">End date</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse ($expiringSoon as $sub)
                            <tr>
                                <td>{{ $sub->member?->first_name }} {{ $sub->member?->last_name }}</td>
                                <td>{{ $sub->member?->phone }}</td>
                                <td>{{ $sub->plan?->name ?? 'Custom' }}</td>
                                <td>
                                    @if (! $sub->plan_id)
                                        <span class="badge bg-info text-dark">Custom</span>
                                    @elseif (($sub->plan?->plan_kind ?? 'individual') === 'couple')
                                        <span class="badge text-bg-warning text-dark">Couple</span>
                                    @else
                                        <span class="text-muted small">Individual</span>
                                    @endif
                                </td>
                                <td class="text-end">{{ $sub->end_date?->toDateString() }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted p-3">No subscriptions expiring in the next 3 days.</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endcan
@endsection

