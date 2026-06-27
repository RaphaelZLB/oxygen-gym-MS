@extends('layouts.app')

@section('title', 'Edit Member')

@section('content')
    <div class="app-page-header">
        <h1 class="h3 app-page-title">Edit Member</h1>
    </div>

    <div class="card app-card">
        <div class="card-body">
            <form method="POST" action="{{ route('members.update', $member) }}">
                @csrf
                @method('PUT')
                <input type="hidden" name="tags_submitted" value="1">

                <div class="row g-3">
                    <div class="col-12 col-md-6">
                        <label class="form-label">First name</label>
                        <input class="form-control @error('first_name') is-invalid @enderror" name="first_name" value="{{ old('first_name', $member->first_name) }}" required>
                        @error('first_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label">Last name</label>
                        <input class="form-control @error('last_name') is-invalid @enderror" name="last_name" value="{{ old('last_name', $member->last_name) }}" required>
                        @error('last_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label">Phone</label>
                        <input class="form-control @error('phone') is-invalid @enderror" name="phone" value="{{ old('phone', $member->phone) }}" required>
                        @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label">Email (optional)</label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email', $member->email) }}">
                        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label">Date of birth (optional)</label>
                        <input type="date" class="form-control @error('date_of_birth') is-invalid @enderror" name="date_of_birth" value="{{ old('date_of_birth', optional($member->date_of_birth)->toDateString()) }}">
                        @error('date_of_birth')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label">Membership status</label>
                        <select class="form-select @error('status') is-invalid @enderror" name="status">
                            <option value="active" @selected(old('status', $member->status) === 'active')>Active</option>
                            <option value="inactive" @selected(old('status', $member->status) === 'inactive')>Inactive</option>
                            <option value="frozen" @selected(old('status', $member->status) === 'frozen')>Frozen</option>
                        </select>
                        @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label">Training time</label>
                        <select class="form-select @error('training_time') is-invalid @enderror" name="training_time">
                            <option value="">—</option>
                            <option value="AM" @selected(old('training_time', $member->training_time) === 'AM')>AM</option>
                            <option value="PM" @selected(old('training_time', $member->training_time) === 'PM')>PM</option>
                        </select>
                        @error('training_time')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-12">
                        <label class="form-label d-block">Member tags</label>
                        @php
                            $tagOptions = ['VIP', 'Athlete', 'Intermediate', 'Beginner'];
                            $currentTags = old('tags', $member->tags ?? []);
                        @endphp
                        @foreach ($tagOptions as $tag)
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" name="tags[]" id="tag_{{ $loop->index }}" value="{{ $tag }}"
                                    @checked(in_array($tag, is_array($currentTags) ? $currentTags : [], true))>
                                <label class="form-check-label" for="tag_{{ $loop->index }}">{{ $tag }}</label>
                            </div>
                        @endforeach
                        @error('tags')<div class="text-danger small">{{ $message }}</div>@enderror
                        @error('tags.*')<div class="text-danger small">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-12">
                        <label class="form-label">Medical notes</label>
                        <textarea class="form-control @error('medical_notes') is-invalid @enderror" name="medical_notes" rows="3">{{ old('medical_notes', $member->medical_notes) }}</textarea>
                        @error('medical_notes')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="mt-3 d-flex flex-wrap gap-2 align-items-center">
                    <button class="btn btn-primary" type="submit">Update</button>
                    @can('manage-payments')
                        <a class="btn btn-outline-secondary" href="{{ route('members.payments.index', $member) }}">Payments</a>
                    @endcan
                    <a class="btn btn-outline-secondary" href="{{ route('members.index') }}">Cancel</a>
                </div>
            </form>

            @can('delete', $member)
                <div class="border-top mt-3 pt-3">
                    <p class="text-muted small mb-2 lh-sm">This will soft-delete the member; related records stay in the database.</p>
                    <form id="member-delete-form" method="POST" action="{{ route('members.destroy', $member) }}">
                        @csrf
                        @method('DELETE')
                    </form>
                    <button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteMemberModal">
                        Delete member
                    </button>
                </div>

                <div class="modal fade" id="deleteMemberModal" tabindex="-1" aria-labelledby="deleteMemberModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header border-secondary">
                                <h5 class="modal-title" id="deleteMemberModalLabel">Delete member?</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <p class="mb-0">Are you sure you want to delete <strong>{{ $member->first_name }} {{ $member->last_name }}</strong>? This member will disappear from member lists. Subscriptions and payment history stay in the database.</p>
                            </div>
                            <div class="modal-footer border-secondary">
                                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" form="member-delete-form" class="btn btn-danger">Yes, delete member</button>
                            </div>
                        </div>
                    </div>
                </div>
            @endcan
        </div>
    </div>
@endsection

