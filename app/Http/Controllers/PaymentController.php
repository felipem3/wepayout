<?php

namespace App\Http\Controllers;

use App\Http\Requests\PaymentStoreRequest;
use App\Services\PaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PaymentController extends Controller
{
    private $paymentService;

    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    public function index()
    {
        return response()->json($this->paymentService->all());
    }

    public function show($id)
    {
        return response()->json($this->paymentService->get($id));
    }

    public function store(PaymentStoreRequest $request)
    {
        $this->paymentService->create(
            $request->value,
            $request->invoice,
            $request->recipient_name,
            $request->recipient_bank_code,
            $request->recipient_branch_number,
            $request->recipient_account_number
        );
        return response()->json([], 201);
    }
}
