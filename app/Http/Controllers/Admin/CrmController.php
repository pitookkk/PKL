<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\ServiceTicket;
use App\Models\InteractionLog;
use App\Services\WhatsAppService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class CrmController extends Controller
{
    protected $whatsAppService;

    public function __construct(WhatsAppService $whatsAppService)
    {
        $this->whatsAppService = $whatsAppService;
    }

    /**
     * Display a listing of all customers.
     */
    public function index(Request $request)
    {
        $query = User::where('role', 'user')->with('orders');

        // Filter by membership level
        if ($request->filled('level')) {
            $query->where('membership_level', $request->level);
        }

        // Sort by total spent
        if ($request->filled('sort_spent')) {
            $query->orderBy('total_spent', $request->sort_spent);
        } else {
            $query->latest(); // Default sort
        }

        $customers = $query->paginate(20)->withQueryString(); // withQueryString appends filter values to pagination links

        return view('admin.crm.index', [
            'customers' => $customers,
            'filters' => $request->only(['level', 'sort_spent'])
        ]);
    }

    /**
     * Display the comprehensive "Customer 360" view for a specific user.
     */
    public function customer360(User $user)
    {
        $user->load(
            'orders.items.product', 
            'customerAssets.maintenanceSchedules', 
            'serviceTickets', 
            'interactionLogs.admin' // Eager load logs and the admin who wrote them
        );
        
        return view('admin.crm.customer-360', compact('user'));
    }

    /**
     * Store a new manual interaction log for a customer.
     */
    public function logInteraction(Request $request, User $user)
    {
        $request->validate(['note' => 'required|string|max:5000']);

        InteractionLog::create([
            'user_id' => $user->id,
            'admin_id' => Auth::id(),
            'note' => $request->note,
        ]);

        return redirect()->route('admin.crm.customer360', $user)->with('success', 'Interaction logged successfully.');
    }

    /**
     * Display the Kanban board for managing service tickets.
     */
    public function ticketsBoard()
    {
        $tickets = ServiceTicket::with('user')->orderBy('created_at', 'desc')->get();
        
        return view('admin.crm.tickets-board', compact('tickets'));
    }

    /**
     * Update the status of a service ticket.
     */
    public function updateTicketStatus(Request $request, ServiceTicket $ticket)
    {
        $validated = $request->validate([
            'status' => 'required|in:open,processing,finished,closed',
        ]);

        $ticket->status = $validated['status'];
        $ticket->save();

        return response()->json(['success' => true, 'message' => 'Ticket status updated.']);
    }

    /**
     * Show the form for creating a new broadcast message.
     */
    public function showBroadcastForm()
    {
        // This will be a new view for the broadcast form
        return view('admin.crm.broadcast');
    }

    /**
     * Send a promotional broadcast message to a group of customers.
     */
    public function sendBroadcast(Request $request)
    {
        $request->validate(['message' => 'required|string|max:1000']);
        
        $sixMonthsAgo = Carbon::now()->subMonths(6);

        // Find users who have not placed an order in the last 6 months
        $inactiveUsers = User::where('role', 'user')
            ->whereDoesntHave('orders', function ($query) use ($sixMonthsAgo) {
                $query->where('created_at', '>=', $sixMonthsAgo);
            })
            ->whereNotNull('phone_number')
            ->get();
        
        $sentCount = 0;
        foreach($inactiveUsers as $user) {
            if ($this->whatsAppService->sendMessage($user->phone_number, $request->message)) {
                $sentCount++;
            }
        }

        return redirect()->route('admin.crm.index')->with('success', "Broadcast sent to {$sentCount} inactive users.");
    }


    // Standard resource controller methods below are mostly for managing users via CRM context
    // but can be adapted as needed.

    public function create()
    {
        return redirect()->route('admin.crm.index');
    }

    public function store(Request $request)
    {
        // Logic to create a new user from the CRM panel
        return redirect()->route('admin.crm.index');
    }

    public function show(User $user)
    {
        return redirect()->route('admin.crm.customer360', $user);
    }

    public function edit(User $user)
    {
        // Logic to show an edit form for a user
        return redirect()->route('admin.crm.index');
    }

    public function update(Request $request, User $user)
    {
        // Logic to update user details
        return redirect()->route('admin.crm.index');
    }

    public function destroy(User $user)
    {
        // Logic to delete a user
        return redirect()->route('admin.crm.index');
    }
}
