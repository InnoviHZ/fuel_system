<?php

namespace App\Http\Controllers;

use App\Models\CreditCustomer;
use App\Http\Requests\StoreCreditCustomerRequest;
use App\Http\Requests\UpdateCreditCustomerRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CreditCustomerController extends Controller
{
    /**
     * Display a listing of credit customers
     */
    public function index()
    {
        // Authorize manager or admin
        if (!Auth::user()->hasRole('manager', 'admin')) {
            abort(403);
        }

        $creditCustomers = CreditCustomer::orderBy('name')->paginate(20);

        return view('credit-customers.index', compact('creditCustomers'));
    }

    /**
     * Show the form for creating a new credit customer
     */
    public function create()
    {
        // Authorize manager or admin
        if (!Auth::user()->hasRole('manager', 'admin')) {
            abort(403);
        }

        return view('credit-customers.create');
    }

    /**
     * Store a newly created credit customer
     */
    public function store(StoreCreditCustomerRequest $request)
    {
        // Authorize manager or admin
        if (!Auth::user()->hasRole('manager', 'admin')) {
            abort(403);
        }

        CreditCustomer::create($request->validated());

        return redirect()->route('credit-customers.index')
            ->with('success', 'Credit customer created successfully');
    }

    /**
     * Display the specified credit customer
     */
    public function show(CreditCustomer $creditCustomer)
    {
        $creditCustomer->load([
            'sales.pump.tank.fuelProduct',
            'sales.fuelProduct',
            'sales.attendant',
            'repayments.receivedBy'
        ]);

        // Get sales for this customer
        $sales = $creditCustomer->sales()
            ->with(['pump.tank.fuelProduct', 'fuelProduct', 'attendant'])
            ->orderBy('sale_time', 'desc')
            ->get();

        // Get repayments for this customer
        $repayments = $creditCustomer->repayments()
            ->with('receivedBy')
            ->orderBy('repayment_date', 'desc')
            ->get();

        return view('credit-customers.show', compact('creditCustomer', 'sales', 'repayments'));
    }

    /**
     * Show the form for editing a credit customer
     */
    public function edit(CreditCustomer $creditCustomer)
    {
        // Authorize manager or admin
        if (!Auth::user()->hasRole('manager', 'admin')) {
            abort(403);
        }

        return view('credit-customers.edit', compact('creditCustomer'));
    }

    /**
     * Update the specified credit customer
     */
    public function update(UpdateCreditCustomerRequest $request, CreditCustomer $creditCustomer)
    {
        // Authorize manager or admin
        if (!Auth::user()->hasRole('manager', 'admin')) {
            abort(403);
        }

        $creditCustomer->update($request->validated());

        return redirect()->route('credit-customers.show', $creditCustomer)
            ->with('success', 'Credit customer updated successfully');
    }
}
