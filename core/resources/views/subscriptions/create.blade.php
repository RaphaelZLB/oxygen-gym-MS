@extends('layouts.app')

@section('title', 'Create Subscription')

@section('content')
    <div class="app-page-header">
        <h1 class="h3 app-page-title">Create Subscription</h1>
    </div>

    <div class="card app-card">
        <div class="card-body">
            <form method="POST" action="{{ route('subscriptions.store') }}">
                @csrf

                <div class="row g-3">
                    <div class="col-12 col-md-6">
                        <label class="form-label">Member</label>
                        <select class="form-select @error('member_id') is-invalid @enderror" name="member_id" required>
                            <option value="">Select member...</option>
                            @foreach($members as $member)
                                <option value="{{ $member->id }}" @selected(old('member_id', request('member_id')) === $member->id)>
                                    {{ $member->first_name }} {{ $member->last_name }} ({{ $member->phone }})
                                </option>
                            @endforeach
                        </select>
                        @error('member_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-12 col-md-6">
                        <label class="form-label">Plan</label>
                        <select class="form-select @error('plan_id') is-invalid @enderror" name="plan_id" id="subscription_plan_id">
                            <option value="" @selected(old('plan_id') === null || old('plan_id') === '')>Custom (no plan)</option>
                            @foreach($plans as $plan)
                                <option value="{{ $plan->id }}" data-price="{{ $plan->price }}" @selected(old('plan_id') === $plan->id)>
                                    {{ $plan->name }}@if(($plan->plan_kind ?? 'individual') === 'couple') (couple)@endif — {{ $plan->duration_days }} days — ${{ number_format((float)$plan->price, 2) }}
                                </option>
                            @endforeach
                        </select>
                        @error('plan_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-12 col-md-4">
                        <label class="form-label">Start date</label>
                        <input type="date" class="form-control @error('start_date') is-invalid @enderror" name="start_date"
                               value="{{ old('start_date', now()->toDateString()) }}" required>
                        @error('start_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-12 col-md-8" id="subscription_plan_based">
                        <div class="row g-3">
                            <div class="col-12 col-md-6">
                                <label class="form-label">Discount (%)</label>
                                <input type="number" step="0.01" min="0" max="100" inputmode="decimal"
                                       class="form-control @error('discount_amount') is-invalid @enderror" name="discount_amount"
                                       id="subscription_discount" value="{{ old('discount_amount', '0') }}">
                                <div class="form-text">Applies to the selected plan price only.</div>
                                @error('discount_amount')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-md-8 d-none" id="subscription_custom_block">
                        <div class="row g-3">
                            <div class="col-12 col-md-6">
                                <label class="form-label">Custom amount (USD)</label>
                                <input type="number" step="0.01" min="0.01" inputmode="decimal"
                                       class="form-control @error('custom_amount') is-invalid @enderror" name="custom_amount"
                                       id="subscription_custom_amount" value="{{ old('custom_amount') }}">
                                @error('custom_amount')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-label">End date</label>
                                <input type="date" class="form-control @error('end_date') is-invalid @enderror" name="end_date"
                                       id="subscription_end_date" value="{{ old('end_date') }}">
                                <div class="form-text">Membership ends on this date (inclusive of the day).</div>
                                @error('end_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-md-4">
                        <label class="form-label">Amount to pay</label>
                        <div class="form-control bg-light" id="subscription_final_display" aria-live="polite">—</div>
                        <div class="form-text">Charged on create.</div>
                    </div>

                    <div class="col-12 col-md-4">
                        <label class="form-label">Payment method</label>
                        <select class="form-select @error('method') is-invalid @enderror" name="method">
                            <option value="cash" @selected(old('method') === 'cash')>cash</option>
                            <option value="wish-money" @selected(old('method') === 'wish-money')>wish-money</option>
                        </select>
                        @error('method')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="mt-3 d-flex gap-2">
                    <button class="btn btn-primary" type="submit">Create</button>
                    <a class="btn btn-outline-secondary" href="{{ route('dashboard') }}">Cancel</a>
                </div>
            </form>
        </div>
    </div>

    <script>
        (function () {
            const planSelect = document.getElementById('subscription_plan_id');
            const planBased = document.getElementById('subscription_plan_based');
            const customBlock = document.getElementById('subscription_custom_block');
            const discountInput = document.getElementById('subscription_discount');
            const customAmountInput = document.getElementById('subscription_custom_amount');
            const endDateInput = document.getElementById('subscription_end_date');
            const out = document.getElementById('subscription_final_display');
            if (!planSelect || !planBased || !customBlock || !discountInput || !customAmountInput || !endDateInput || !out) return;

            function parseMoney(v) {
                const n = parseFloat(String(v).replace(',', '.'));
                return Number.isFinite(n) ? n : 0;
            }

            function isCustom() {
                return !planSelect.value;
            }

            function update() {
                if (isCustom()) {
                    planBased.classList.add('d-none');
                    customBlock.classList.remove('d-none');
                    discountInput.disabled = true;
                    customAmountInput.disabled = false;
                    endDateInput.disabled = false;
                    const amt = parseMoney(customAmountInput.value);
                    if (amt <= 0) {
                        out.textContent = '—';
                        out.classList.add('text-danger', 'fw-semibold');
                    } else {
                        out.textContent = '$' + amt.toFixed(2);
                        out.classList.remove('text-danger', 'fw-semibold');
                    }
                    return;
                }

                planBased.classList.remove('d-none');
                customBlock.classList.add('d-none');
                discountInput.disabled = false;
                customAmountInput.disabled = true;
                endDateInput.disabled = true;

                const opt = planSelect.selectedOptions[0];
                const price = opt ? parseMoney(opt.getAttribute('data-price')) : 0;
                let pct = Math.max(0, parseMoney(discountInput.value));
                if (pct > 100) {
                    pct = 100;
                }
                const final = Math.max(0, Math.round(price * (1 - pct / 100) * 100) / 100);
                if (!opt || !planSelect.value) {
                    out.textContent = '—';
                    return;
                }
                out.textContent = '$' + final.toFixed(2);
                if (final <= 0) {
                    out.classList.add('text-danger', 'fw-semibold');
                } else {
                    out.classList.remove('text-danger', 'fw-semibold');
                }
            }

            planSelect.addEventListener('change', update);
            discountInput.addEventListener('input', update);
            customAmountInput.addEventListener('input', update);
            update();
        })();
    </script>
@endsection
