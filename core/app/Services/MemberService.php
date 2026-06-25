<?php

namespace App\Services;

use App\Models\Member;
use App\Repositories\Contracts\MemberRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class MemberService
{
    public function __construct(
        private readonly MemberRepository $members,
    ) {
    }

    public function paginate(?string $search, int $perPage = 15, ?string $status = null): LengthAwarePaginator
    {
        return $this->members->paginate($search, $perPage, $status);
    }

    public function create(array $data): Member
    {
        $data = $this->normalizeMemberTags($data);

        return $this->members->create($data);
    }

    public function update(Member $member, array $data): Member
    {
        $data = $this->normalizeMemberTags($data);

        return $this->members->update($member, $data);
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    private function normalizeMemberTags(array $data): array
    {
        if (array_key_exists('tags', $data) && ($data['tags'] === [] || $data['tags'] === null)) {
            $data['tags'] = null;
        }

        return $data;
    }

    public function delete(Member $member): void
    {
        $this->members->delete($member);
    }
}

