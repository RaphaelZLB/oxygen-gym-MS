@extends('layouts.app')

@section('title', 'Payments')

@section('content')
    <div class="d-flex align-items-center justify-content-between app-page-header">
        <div>
            <h1 class="h3 app-page-title">Payments</h1>
            <div class="text-muted small">{{ $member->first_name }} {{ $member->last_name }} — {{ $member->phone }}</div>
        </div>
        <a class="btn btn-outline-secondary" href="{{ route('members.index') }}">Back to members</a>
    </div>

    <div class="card app-card app-card-table">
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                <tr>
                    <th>Paid at</th>
                    <th class="text-end">Amount</th>
                    <th>Method</th>
                    <th>Plan</th>
                    <th>Type</th>
                    <th>Price</th>
                    <th>Status</th>
                </tr>
                </thead>
                <tbody>
                @forelse($payments as $payment)
                    <tr>
                        <td>{{ $payment->paid_at?->toDateTimeString() }}</td>
                        <td class="text-end">${{ number_format((float)$payment->amount, 2) }}</td>
                        <td><span class="badge bg-secondary text-capitalize">{{ $payment->method }}</span></td>
                        <td>{{ $payment->subscription?->plan?->name ?? 'Custom' }}</td>
                        <td>
                            @if ($payment->subscription && ! $payment->subscription->plan_id)
                                <span class="text-muted">—</span>
                            @else
                                {{ $payment->subscription?->plan?->plan_kind }}
                            @endif
                        </td>
                        <td>
                            @if ($payment->subscription && ! $payment->subscription->plan_id)
                                {{ $payment->subscription->final_price !== null ? '$'.number_format((float) $payment->subscription->final_price, 2) : '—' }}
                            @else
                                {{ $payment->subscription?->plan?->price }}
                            @endif
                        </td>
                        <td>
                            @if ($payment->subscription?->end_date?->isPast())
                                <span class="badge bg-danger">expired</span>
                            @else
                                <span class="badge bg-success">active</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center text-muted p-3">No payments found.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3">
        {{ $payments->withQueryString()->links('pagination::bootstrap-5') }}
    </div>
@endsection

