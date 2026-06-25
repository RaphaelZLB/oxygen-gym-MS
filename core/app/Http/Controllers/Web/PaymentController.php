<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Member;
use App\Services\PaymentService;
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

        return view('payments.index', [
            'member' => $member,
            'payments' => $payments,
        ]);
    }
}
