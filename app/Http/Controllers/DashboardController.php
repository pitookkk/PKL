<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Display the user dashboard.
     */
    public function index()
    {
        $user = Auth::user();
        $orders = $user->orders()->with('items')->latest()->paginate(10);

        return view('dashboard.index', compact('user', 'orders'));
    }
}
