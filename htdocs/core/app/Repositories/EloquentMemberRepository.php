<?php

namespace App\Repositories;

use App\Models\Member;
use App\Repositories\Contracts\MemberRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class EloquentMemberRepository implements MemberRepository
{
    public function paginate(?string $search, int $perPage = 15, ?string $status = null): LengthAwarePaginator
    {
        $query = Member::query();

        if ($search !== null && $search !== '') {
            $search = trim($search);
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        if ($status !== null && $status !== '') {
            $query->where('status', $status);
        }

        $query->with([
            'subscriptions' => function ($q) {
                $q->where('status', 'active')
                    ->whereDate('end_date', '>=', now()->toDateString())
                    ->with(['plan', 'payments'])
                    ->orderByDesc('start_date');
            },
        ]);

        return $query->orderBy('created_at', 'desc')->paginate($perPage);
    }

    public function create(array $data): Member
    {
        return Member::create($data);
    }

    public function update(Member $member, array $data): Member
    {
        $member->fill($data);
        $member->save();

        return $member;
    }

    public function delete(Member $member): void
    {
        $member->delete();
    }
}

