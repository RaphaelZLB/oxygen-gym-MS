<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreMemberRequest;
use App\Http\Requests\UpdateMemberRequest;
use App\Http\Resources\MemberResource;
use App\Http\Responses\ApiResponse;
use App\Models\Member;
use App\Services\MemberService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MemberController extends Controller
{
    public function __construct(
        private readonly MemberService $members,
    ) {
    }

    public function index(Request $request): JsonResponse
    {
        $this->authorize('viewAny', Member::class);

        $perPage = (int) $request->query('per_page', 15);
        $search = $request->query('q');

        $result = $this->members->paginate(is_string($search) ? $search : null, $perPage);

        $payload = MemberResource::collection($result)->response()->getData(true);

        return ApiResponse::success('', $payload);
    }

    public function store(StoreMemberRequest $request): JsonResponse
    {
        $this->authorize('create', Member::class);

        $member = $this->members->create($request->validated());

        return ApiResponse::success('Member created.', new MemberResource($member), 201);
    }

    public function show(Member $member): JsonResponse
    {
        $this->authorize('view', $member);

        $member->load('user.roles');

        return ApiResponse::success('', new MemberResource($member));
    }

    public function update(UpdateMemberRequest $request, Member $member): JsonResponse
    {
        $this->authorize('update', $member);

        $member = $this->members->update($member, $request->validated());

        return ApiResponse::success('Member updated.', new MemberResource($member));
    }

    public function destroy(Member $member): JsonResponse
    {
        $this->authorize('delete', $member);

        $this->members->delete($member);

        return ApiResponse::success('Member deleted.');
    }
}

