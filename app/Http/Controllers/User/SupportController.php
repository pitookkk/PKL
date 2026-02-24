<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\ServiceTicket;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class SupportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user()->load(['customerAssets', 'serviceTickets' => function ($query) {
            $query->latest();
        }]);
        
        return view('pitocom-care.my-pitocom-care', compact('user'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Form is on the index page, so this is not needed.
        return redirect()->route('pitocom-care.dashboard');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'subject' => 'required|string|max:255',
            'description' => 'required|string',
            'priority' => 'required|in:low,medium,high',
        ]);

        ServiceTicket::create([
            'user_id' => Auth::id(),
            'ticket_number' => 'TICKET-' . Str::upper(Str::random(8)),
            'subject' => $request->subject,
            'description' => $request->description,
            'priority' => $request->priority,
            'status' => 'open',
        ]);

        return back()->with('success', 'Support ticket created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(ServiceTicket $serviceTicket)
    {
        // Optional: Create a detailed view for a single ticket
        // For now, redirect back to the dashboard.
        return redirect()->route('pitocom-care.dashboard');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ServiceTicket $serviceTicket)
    {
        // Users typically don't edit tickets, they might add replies.
        // For now, this is out of scope.
        return redirect()->route('pitocom-care.dashboard');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ServiceTicket $serviceTicket)
    {
        // Logic for users to update their tickets (e.g., add a comment)
        return redirect()->route('pitocom-care.dashboard');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ServiceTicket $serviceTicket)
    {
        // Logic for users to cancel/delete their tickets
        return redirect()->route('pitocom-care.dashboard');
    }
}
