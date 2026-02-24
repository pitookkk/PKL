<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\CartItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * Show registration form.
     */
    public function showRegister()
    {
        return view('auth.register');
    }

    /**
     * Handle registration.
     */
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'user', 
        ]);

        Auth::login($user);
        $this->syncSessionCartToDatabase();

        return redirect('/dashboard')->with('success', 'Registration successful.');
    }

    /**
     * Show login form.
     */
    public function showLogin()
    {
        return view('auth.login');
    }

    /**
     * Handle login.
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            $this->syncSessionCartToDatabase();

            if (Auth::user()->isAdmin()) {
                return redirect()->intended('admin/dashboard');
            }

            return redirect()->intended('dashboard');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    /**
     * Move items from session cart to database.
     */
    protected function syncSessionCartToDatabase()
    {
        $sessionCart = session()->get('cart', []);
        
        foreach ($sessionCart as $key => $details) {
            if ($details['type'] === 'bundle') {
                CartItem::updateOrCreate([
                    'user_id' => Auth::id(),
                    'bundle_id' => $details['bundle_id'],
                    'type' => 'bundle'
                ], ['quantity' => $details['quantity']]);
            } else {
                $ids = explode('-', $key);
                CartItem::updateOrCreate([
                    'user_id' => Auth::id(),
                    'product_id' => $details['product_id'],
                    'product_variation_id' => $ids[1] ?? null,
                    'type' => 'product'
                ], ['quantity' => $details['quantity']]);
            }
        }

        session()->forget('cart');
    }

    /**
     * Handle logout.
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
