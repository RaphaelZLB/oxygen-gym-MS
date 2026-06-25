@extends('layouts.app')

@section('title', 'Members')

@section('content')
    <div class="d-flex align-items-center justify-content-between app-page-header">
        <h1 class="h3 app-page-title">Members</h1>
        <a class="btn btn-primary" href="{{ route('members.create') }}">+ Add Member</a>
    </div>

    <form class="row g-2 mb-3" method="GET" action="{{ route('members.index') }}">
        <div class="col-12 col-md-6">
            <input class="form-control" type="text" name="q" value="{{ $q }}"
                placeholder="Search by name or phone">
        </div>
        <div class="col-12 col-md-4">
            <select class="form-select" name="status">
                <option value="all" @selected(($status ?? 'all') === 'all')>All</option>
                <option value="active" @selected(($status ?? '') === 'active')>Active</option>
                <option value="inactive" @selected(($status ?? '') === 'inactive')>Non-active</option>
                <option value="frozen" @selected(($status ?? '') === 'frozen')>Frozen</option>
            </select>
        </div>
        <div class="col-12 col-md-auto">
            <button class="btn btn-outline-secondary" type="submit">Search</button>
            <a class="btn btn-link" href="{{ route('members.index') }}">Reset</a>
        </div>
    </form>

    <div class="card app-card app-card-table">
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Status</th>
                        <th>Plan type</th>
                        <th>Tags</th>
                        <th>Medical notes</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($members as $member)
                        @php
                            $currentSub = $member->subscriptions->first();
                            $planKind = $currentSub?->plan?->plan_kind ?? 'individual';
                        @endphp
                        <tr>
                            <td>{{ $member->first_name }} {{ $member->last_name }}</td>
                            <td>
                                @if ($member->status === 'active')
                                    <span class="badge bg-success">active</span>
                                @elseif ($member->status === 'frozen')
                                    <span class="badge bg-info text-dark">frozen</span>
                                @else
                                    <span class="badge bg-secondary">inactive</span>
                                @endif
                            </td>
                            <td>
                                @if ($currentSub && $currentSub->plan_id && $currentSub->plan)
                                    @if ($planKind === 'couple')
                                        <span class="badge text-bg-warning text-dark">Couple</span>
                                    @else
                                        <span class="text-muted small">Individual</span>
                                    @endif
                                @elseif ($currentSub && ! $currentSub->plan_id)
                                    <span class="badge bg-info text-dark">Custom</span>
                                @else
                                    <span class="text-muted small">—</span>
                                @endif
                            </td>
                            <td>
                                @if (!empty($member->tags) && is_array($member->tags))
                                    @foreach ($member->tags as $tag)
                                        <span class="badge bg-primary me-1">{{ $tag }}</span>
                                    @endforeach
                                @else
                                    <span class="text-muted small">—</span>
                                @endif
                            </td>
                            <td>
                                @if (!empty($member->medical_notes))
                                    <span class="text-muted small">{{ $member->medical_notes }}</span>
                                @else
                                    <span class="text-muted small">—</span>
                                @endif
                            </td>
                            <td class="text-end">
                                <a class="btn btn-sm btn-outline-primary"
                                    href="{{ route('members.show', $member) }}">View</a>
                                <a class="btn btn-sm btn-outline-secondary"
                                    href="{{ route('members.payments.index', $member) }}">Payments</a>
                                <a class="btn btn-sm btn-outline-primary"
                                    href="{{ route('members.edit', $member) }}">Edit</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted p-3">No members found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3">
        {{ $members->withQueryString()->links('pagination::bootstrap-5') }}
    </div>
@endsection
