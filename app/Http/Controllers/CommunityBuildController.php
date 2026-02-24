<?php

namespace App\Http\Controllers;

use App\Models\CommunityBuild;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class CommunityBuildController extends Controller
{
    /**
     * Display the gallery of approved community builds.
     */
    public function index()
    {
        $builds = CommunityBuild::with(['user', 'products'])
            ->where('status', 'approved')
            ->latest()
            ->paginate(12);

        return view('community-builds.index', compact('builds'));
    }

    /**
     * Show form to create a new build post.
     */
    public function create()
    {
        // Get products the user has actually bought to suggest them
        $userProducts = Product::whereHas('orderItems.order', function($q) {
            $q->where('user_id', Auth::id())->where('status', 'completed');
        })->distinct()->get();

        return view('community-builds.create', compact('userProducts'));
    }

    /**
     * Store a new community build post.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'required|image|max:5120', // Max 5MB
            'product_ids' => 'required|array|min:1',
            'product_ids.*' => 'exists:products,id',
        ]);

        $path = $request->file('image')->store('community_builds', 'public');

        $build = CommunityBuild::create([
            'user_id' => Auth::id(),
            'title' => $request->title,
            'description' => $request->description,
            'image_path' => $path,
            'status' => 'pending', // Needs admin approval
        ]);

        $build->products()->attach($request->product_ids);

        return redirect()->route('community-builds.index')
            ->with('success', 'Build submitted! It will appear in the gallery after admin review.');
    }

    /**
     * Admin: List all builds for moderation.
     */
    public function adminIndex()
    {
        $builds = CommunityBuild::with('user')->latest()->paginate(20);
        return view('admin.community-builds.index', compact('builds'));
    }

    /**
     * Admin: Approve a build.
     */
    public function approve(CommunityBuild $build)
    {
        $build->update(['status' => 'approved']);
        return back()->with('success', 'Build approved and published.');
    }

    /**
     * Admin: Reject a build.
     */
    public function reject(CommunityBuild $build)
    {
        $build->update(['status' => 'rejected']);
        return back()->with('success', 'Build rejected.');
    }
}
