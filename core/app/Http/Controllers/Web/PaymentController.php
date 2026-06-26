<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePaymentRequest;
use App\Models\Member;
use App\Services\PaymentService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PaymentController extends Controller
{
    public function __construct(
        private readonly PaymentService $payments,
    ) {
    }

    public function index(Request $request, Member $member): View
    {
        $perPage = (int) $request->query('per_page', 15);
        $payments = $this->payments->paginateForMember($member->id, $perPage);
        $subscriptionsWithDue = $this->payments->subscriptionsWithBalanceDue($member->id);

        return view('payments.index', [
            'member' => $member,
            'payments' => $payments,
            'subscriptionsWithDue' => $subscriptionsWithDue,
        ]);
    }

    public function store(StorePaymentRequest $request, Member $member): RedirectResponse
    {
        $this->payments->recordPayment([
            ...$request->validated(),
            'member_id' => $member->id,
        ]);

        return redirect()
            ->route('members.payments.index', $member)
            ->with('success', 'Payment recorded.');
    }
}
