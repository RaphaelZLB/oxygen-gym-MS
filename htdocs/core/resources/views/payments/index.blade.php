@extends('layouts.app')

@section('title', 'Payments')

@section('content')
    <div class="d-flex align-items-center justify-content-between app-page-header gap-3 flex-wrap">
        <div>
            <h1 class="h3 app-page-title">Payments</h1>
            <div class="text-muted small">{{ $member->first_name }} {{ $member->last_name }} — {{ $member->phone }}</div>
        </div>
        <div class="d-flex gap-2">
            <a class="btn btn-outline-secondary" href="{{ route('members.show', $member) }}">Member details</a>
            <a class="btn btn-outline-secondary" href="{{ route('members.index') }}">Back to members</a>
        </div>
    </div>

    @can('manage-payments')
        @if ($subscriptionsWithDue->isNotEmpty())
            <div class="card app-card mb-4">
                <div class="card-header">Record payment</div>
                <div class="card-body">
                    <form method="POST" action="{{ route('members.payments.store', $member) }}">
                        @csrf

                        <div class="row g-3">
                            <div class="col-12 col-md-6">
                                <label class="form-label">Subscription</label>
                                <select class="form-select @error('subscription_id') is-invalid @enderror" name="subscription_id"
                                    id="payment_subscription_id" required>
                                    <option value="">Select subscription...</option>
                                    @foreach ($subscriptionsWithDue as $subscription)
                                        <option value="{{ $subscription->id }}"
                                            data-balance="{{ number_format($subscription->balanceDue(), 2, '.', '') }}"
                                            @selected(old('subscription_id') === $subscription->id)>
                                            {{ $subscription->plan?->name ?? 'Custom' }}
                                            (ends {{ $subscription->end_date?->toDateString() }})
                                            — due ${{ number_format($subscription->balanceDue(), 2) }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('subscription_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-12 col-md-3">
                                <label class="form-label">Amount (USD)</label>
                                <input type="number" step="0.01" min="0.01" inputmode="decimal"
                                    class="form-control @error('amount') is-invalid @enderror" name="amount"
                                    id="payment_amount" value="{{ old('amount') }}" required>
                                <div class="form-text" id="payment_balance_hint">Select a subscription to see the remaining balance.</div>
                                @error('amount')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-12 col-md-3">
                                <label class="form-label">Paid at</label>
                                <input type="datetime-local" class="form-control @error('paid_at') is-invalid @enderror"
                                    name="paid_at" value="{{ old('paid_at', now()->format('Y-m-d\TH:i')) }}" required>
                                @error('paid_at')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-12 col-md-4">
                                <label class="form-label">Payment method</label>
                                <select class="form-select @error('method') is-invalid @enderror" name="method" required>
                                    <option value="cash" @selected(old('method', 'cash') === 'cash')>cash</option>
                                    <option value="wish-money" @selected(old('method') === 'wish-money')>wish-money</option>
                                </select>
                                @error('method')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div class="mt-3">
                            <button class="btn btn-primary" type="submit">Record payment</button>
                        </div>
                    </form>
                </div>
            </div>
        @else
            <div class="alert app-alert-success mb-4">This member has no outstanding subscription balances.</div>
        @endif
    @endcan

    <div class="card app-card app-card-table">
        <div class="card-header">Payment history</div>
        <div class="table-responsive">
            <table class="table table-striped mb-0">
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
                        <td class="text-end">${{ number_format((float) $payment->amount, 2) }}</td>
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
                        <td colspan="7" class="text-center text-muted p-3">No payments found.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3">
        {{ $payments->withQueryString()->links('pagination::bootstrap-5') }}
    </div>

    @can('manage-payments')
        @if ($subscriptionsWithDue->isNotEmpty())
            <script>
                (function () {
                    const subscriptionSelect = document.getElementById('payment_subscription_id');
                    const amountInput = document.getElementById('payment_amount');
                    const balanceHint = document.getElementById('payment_balance_hint');
                    if (!subscriptionSelect || !amountInput || !balanceHint) return;

                    function updateBalanceHint() {
                        const option = subscriptionSelect.selectedOptions[0];
                        const balance = option ? parseFloat(option.getAttribute('data-balance') || '0') : 0;
                        if (!option || !subscriptionSelect.value || balance <= 0) {
                            balanceHint.textContent = 'Select a subscription to see the remaining balance.';
                            return;
                        }

                        balanceHint.textContent = 'Remaining balance: $' + balance.toFixed(2);
                        if (!amountInput.value) {
                            amountInput.value = balance.toFixed(2);
                        }
                    }

                    subscriptionSelect.addEventListener('change', updateBalanceHint);
                    updateBalanceHint();
                })();
            </script>
        @endif
    @endcan
@endsection
