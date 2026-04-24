@extends('layouts.app')

@section('title', 'Member Details')

@section('content')
    <div class="d-flex align-items-center justify-content-between app-page-header gap-3 flex-wrap">
        <div>
            <h1 class="h3 app-page-title">
                {{ $member->first_name }} {{ $member->last_name }}
            </h1>
            <div class="text-muted small">
                {{ $member->phone }}
                @if (!empty($member->email))
                    • {{ $member->email }}
                @endif
            </div>
        </div>

        <div class="d-flex gap-2">
            <a class="btn btn-outline-secondary" href="{{ route('members.index') }}">Back</a>
            @can('manage-members')
                <a class="btn btn-primary" href="{{ route('members.edit', $member) }}">Edit</a>
            @endcan
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-12 col-md-4">
            <div class="card app-card">
                <div class="card-body">
                    <div class="text-muted small">Membership status</div>
                    @if ($member->status === 'active')
                        <div><span class="badge bg-success">active</span></div>
                    @elseif ($member->status === 'frozen')
                        <div><span class="badge bg-info text-dark">frozen</span></div>
                    @else
                        <div><span class="badge bg-secondary">inactive</span></div>
                    @endif
                    @if (!empty($member->training_time))
                        <div class="mt-2 text-muted small">Training: {{ $member->training_time }}</div>
                    @endif
                    @if (!empty($member->date_of_birth))
                        <div class="mt-2 text-muted small">DOB: {{ $member->date_of_birth->toDateString() }}</div>
                    @endif
                    @if (!empty($member->tags) && is_array($member->tags))
                        <div class="mt-2">
                            @foreach ($member->tags as $tag)
                                <span class="badge bg-primary me-1">{{ $tag }}</span>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
        @if (!empty($member->medical_notes))
            <div class="col-12 col-md-8">
                <div class="card app-card">
                    <div class="card-body">
                        <div class="text-muted small mb-1">Medical notes</div>
                        <div class="small">{{ $member->medical_notes }}</div>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <div class="card app-card app-card-table mb-4">
        <div class="card-header">Subscriptions</div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped mb-0">
                    <thead>
                    <tr>
                        <th>Plan</th>
                        <th>Type</th>
                        <th>Start date</th>
                        <th class="text-end">End date</th>
                        <th class="text-end">Paid / Due</th>
                        <th class="text-end">Status</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse ($subscriptions as $sub)
                        @php
                            $paidTotal = $sub->payments->sum('amount');
                            $due = $sub->final_price !== null ? max(0, (float) $sub->final_price - (float) $paidTotal) : 0;
                            $needsPay = $due > 0.009;
                        @endphp
                        <tr>
                            <td>
                                {{ $sub->plan?->name ?? 'Custom' }}
                                @if ($sub->is_renewal)
                                    <span class="badge bg-secondary ms-1">Renewal</span>
                                @else
                                    <span class="badge bg-success ms-1">New</span>
                                @endif
                            </td>
                            <td>
                                @if (! $sub->plan_id)
                                    <span class="badge bg-info text-dark">Custom</span>
                                @elseif (($sub->plan?->plan_kind ?? 'individual') === 'couple')
                                    <span class="badge text-bg-warning text-dark">Couple</span>
                                @else
                                    <span class="text-muted small">Individual</span>
                                @endif
                            </td>
                            <td>{{ $sub->start_date?->toDateString() }}</td>
                            <td class="text-end">{{ $sub->end_date?->toDateString() }}</td>
                            <td class="text-end">
                                <span class="@if($needsPay) text-danger fw-semibold @endif">
                                    ${{ number_format((float) $paidTotal, 2) }}
                                    @if ($sub->final_price !== null)
                                        / ${{ number_format((float) $sub->final_price, 2) }}
                                    @endif
                                </span>
                                @if ($needsPay)
                                    <div class="small text-danger">Payment due: ${{ number_format($due, 2) }}</div>
                                @endif
                            </td>
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
                            <td colspan="6" class="text-center text-muted p-3">No subscriptions found.</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @can('manage-payments')
        <div class="card app-card">
            <div class="card-header">Payment History (Last 3)</div>
            <div class="card-body">
                @if ($recentPayments->isEmpty())
                    <div class="text-center text-muted">No payments found.</div>
                @else
                    <div class="list-group list-group-flush">
                        @foreach ($recentPayments as $payment)
                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <div class="fw-semibold">
                                        ${{ number_format((float) $payment->amount, 2) }}
                                        <span class="text-muted fw-normal small">• {{ ucfirst($payment->method) }}</span>
                                    </div>
                                    <div class="text-muted small">
                                        Paid: {{ $payment->paid_at?->toDateTimeString() }} • Plan: {{ $payment->subscription?->plan?->name ?? 'Custom' }}
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    @endcan
@endsection

