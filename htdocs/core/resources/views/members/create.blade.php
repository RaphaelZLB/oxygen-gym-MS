@extends('layouts.app')

@section('title', 'Create Member')

@section('content')
    <div class="app-page-header">
        <h1 class="h3 app-page-title">Create Member</h1>
    </div>

    <div class="card app-card">
        <div class="card-body">
            <form method="POST" action="{{ route('members.store') }}">
                @csrf

                <div class="row g-3">
                    <div class="col-12 col-md-6">
                        <label class="form-label">First name</label>
                        <input class="form-control @error('first_name') is-invalid @enderror" name="first_name" value="{{ old('first_name') }}" required>
                        @error('first_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label">Last name</label>
                        <input class="form-control @error('last_name') is-invalid @enderror" name="last_name" value="{{ old('last_name') }}" required>
                        @error('last_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label">Phone</label>
                        <input class="form-control @error('phone') is-invalid @enderror" name="phone" value="{{ old('phone') }}" required>
                        @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label">Email (optional)</label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}">
                        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label">Date of birth (optional)</label>
                        <input type="date" class="form-control @error('date_of_birth') is-invalid @enderror" name="date_of_birth" value="{{ old('date_of_birth') }}">
                        @error('date_of_birth')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label" for="member-create-status">Membership status</label>
                        <select id="member-create-status" class="form-select @error('status') is-invalid @enderror" name="status">
                            <option value="active" @selected(old('status', 'active') === 'active')>Active</option>
                            <option value="inactive" @selected(old('status') === 'inactive')>Inactive</option>
                            <option value="frozen" @selected(old('status') === 'frozen')>Frozen</option>
                        </select>
                        @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label">Training time</label>
                        <select class="form-select @error('training_time') is-invalid @enderror" name="training_time">
                            <option value="">—</option>
                            <option value="AM" @selected(old('training_time') === 'AM')>AM</option>
                            <option value="PM" @selected(old('training_time') === 'PM')>PM</option>
                        </select>
                        @error('training_time')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-12">
                        <label class="form-label d-block">Member tags</label>
                        @php $tagOptions = ['VIP', 'Athlete', 'Intermediate', 'Beginner']; @endphp
                        @foreach ($tagOptions as $tag)
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" name="tags[]" id="tag_{{ $loop->index }}" value="{{ $tag }}"
                                    @checked(in_array($tag, old('tags', []), true))>
                                <label class="form-check-label" for="tag_{{ $loop->index }}">{{ $tag }}</label>
                            </div>
                        @endforeach
                        @error('tags')<div class="text-danger small">{{ $message }}</div>@enderror
                        @error('tags.*')<div class="text-danger small">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-12">
                        <label class="form-label">Medical notes</label>
                        <textarea class="form-control @error('medical_notes') is-invalid @enderror" name="medical_notes" rows="3">{{ old('medical_notes') }}</textarea>
                        @error('medical_notes')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                @error('next_step')
                    <div class="text-danger small mt-3">{{ $message }}</div>
                @enderror

                <div class="mt-3 d-flex flex-wrap gap-2 align-items-center">
                    <button class="btn btn-primary" type="submit">Create</button>
                    <button id="member-create-add-payment-btn" class="btn btn-primary" type="submit" name="next_step"
                        value="subscription" @disabled(old('status', 'active') !== 'active')>
                        Create &amp; Add payment
                    </button>
                    <a class="btn btn-outline-secondary" href="{{ route('members.index') }}">Cancel</a>
                </div>
            </form>
        </div>
    </div>

    <script>
        (function() {
            const statusEl = document.getElementById('member-create-status');
            const addPayBtn = document.getElementById('member-create-add-payment-btn');
            if (!statusEl || !addPayBtn) return;

            const disabledTitle = 'Only available when membership status is Active.';

            function syncAddPaymentButton() {
                const active = statusEl.value === 'active';
                addPayBtn.disabled = !active;
                addPayBtn.title = active ? '' : disabledTitle;
            }

            statusEl.addEventListener('change', syncAddPaymentButton);
            syncAddPaymentButton();
        })();
    </script>
@endsection

