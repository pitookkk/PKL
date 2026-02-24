<?php

namespace App\Http\Controllers;

use App\Models\CustomerAsset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class CustomerAssetController extends Controller
{
    public function __construct()
    {
        // Protect the 'store' method so only logged-in users can add assets
        $this->middleware('auth')->only(['store']);
    }

    /**
     * Display the specified resource.
     * This page is intended to be publicly accessible via a QR code.
     *
     * @param  \App\Models\CustomerAsset  $customerAsset
     * @return \Illuminate\View\View
     */
    public function show(CustomerAsset $customerAsset)
    {
        // You might want to add some authentication or authorization logic here
        // if these pages shouldn't be fully public. For now, we'll assume they are.
        
        $customerAsset->load('user'); // Load the user relationship

        return view('customer-assets.show', compact('customerAsset'));
    }

    /**
     * Store a new manually added asset for the authenticated user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'device_name' => 'required|string|max:255',
            'purchase_date' => 'required|date',
            'warranty_expiry' => 'nullable|date|after_or_equal:purchase_date',
            'specifications' => 'nullable|json',
        ]);

        CustomerAsset::create([
            'user_id' => Auth::id(),
            'device_name' => $request->device_name,
            'purchase_date' => $request->purchase_date,
            'warranty_expiry' => $request->warranty_expiry,
            'specifications' => $request->specifications ? json_decode($request->specifications, true) : null,
        ]);

        return back()->with('success', 'Hardware asset added successfully!');
    }
}

