<?php

namespace App\Http\Controllers;

use App\Models\Bundle;
use Illuminate\Http\Request;

class BundleController extends Controller
{
    /**
     * Display a listing of active bundles.
     */
    public function index()
    {
        $bundles = Bundle::with('products')
            ->where('is_active', true)
            ->latest()
            ->paginate(12);

        return view('bundles.index', compact('bundles'));
    }
}
