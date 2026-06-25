<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreMemberRequest;
use App\Http\Requests\UpdateMemberRequest;
use App\Models\Member;
use App\Services\MemberService;
use App\Services\PaymentService;
use App\Services\SubscriptionService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Gate;

class MemberController extends Controller
{
    public function __construct(
        private readonly MemberService $members,
        private readonly SubscriptionService $subscriptions,
        private readonly PaymentService $payments,
    ) {
    }

    public function index(Request $request): View
    {
        $search = $request->query('q');
        $status = $request->query('status');
        $perPage = (int) $request->query('per_page', 15);

        $members = $this->members->paginate(
            is_string($search) ? $search : null,
            $perPage,
            is_string($status) && in_array($status, ['active', 'inactive', 'frozen'], true) ? $status : null
        );

        return view('members.index', [
            'members' => $members,
            'q' => is_string($search) ? $search : '',
            'status' => is_string($status) ? $status : 'all',
        ]);
    }

    public function create(): View
    {
        return view('members.create');
    }

    public function store(StoreMemberRequest $request): RedirectResponse
    {
        $nextStep = $request->string('next_step')->toString();
        $data = $request->safe()->except(['next_step']);

        $member = $this->members->create($data);

        if ($nextStep === 'subscription') {
            return redirect()
                ->route('subscriptions.create', ['member_id' => $member->id])
                ->with('success', 'Member created. Add a subscription below.');
        }

        return redirect()->route('members.index')->with('success', 'Member created.');
    }

    public function edit(Member $member): View
    {
        return view('members.edit', [
            'member' => $member,
        ]);
    }

    public function show(Member $member): View
    {   
        Gate::authorize('view', $member);

        $subscriptions = $this->subscriptions->allByMember($member->id);
        $recentPayments = $this->payments->lastForMember($member->id, 3);

        return view('members.show', [
            'member' => $member,
            'subscriptions' => $subscriptions,
            'recentPayments' => $recentPayments,
        ]);
    }

    public function update(UpdateMemberRequest $request, Member $member): RedirectResponse
    {
        $this->members->update($member, $request->validated());

        return redirect()->route('members.index')->with('success', 'Member updated.');
    }

    public function destroy(Member $member): RedirectResponse
    {
        Gate::authorize('delete', $member);

        $this->members->delete($member);

        return redirect()->route('members.index')->with('success', 'Member removed.');
    }
}
