<?php

namespace App\Repositories\Contracts;

use App\Models\Member;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface MemberRepository
{
    public function paginate(?string $search, int $perPage = 15, ?string $status = null): LengthAwarePaginator;

    public function create(array $data): Member;

    public function update(Member $member, array $data): Member;

    public function delete(Member $member): void;
}

