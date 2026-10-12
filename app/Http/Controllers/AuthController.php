<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Order;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * Show the login form.
     */
    public function showLogin(Request $request)
    {
        if (Auth::check()) {
            return $this->redirectUser();
        }

        return view('auth.login');
    }

    /**
     * Handle authentication attempt.
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt([
            'email' => $credentials['email'],
            'password' => $credentials['password'],
        ], $request->has('remember'))) {
            $request->session()->regenerate();
            return $this->redirectUser();
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->onlyInput('email');
    }

    /**
     * Show registration form.
     */
    public function showRegister()
    {
        if (Auth::check()) {
            return $this->redirectUser();
        }
        return view('auth.register');
    }

    /**
     * Handle user registration.
     */
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'phone' => 'nullable|string|max:15',
            'address' => 'nullable|string',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'customer',
            'phone' => $request->phone,
            'address' => $request->address,
        ]);

        Auth::login($user);

        return redirect()->route('customer.dashboard')->with('success', 'Registrasi berhasil! Selamat datang di Dapur Mama Delisha.');
    }

    /**
     * Log user out of the application.
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login')->with('success', 'Anda telah berhasil keluar.');
    }

    /**
     * Show customer profile page.
     */
    public function showProfile()
    {
        $user = Auth::user();

        $totalOrders = Order::where('user_id', $user->id)->count();
        $completedOrders = Order::where('user_id', $user->id)->where('status', 'Selesai')->count();
        $processingOrders = Order::where('user_id', $user->id)->whereIn('status', ['Menunggu', 'Diproses'])->count();
        $cancelledOrders = Order::where('user_id', $user->id)->where('status', 'Dibatalkan')->count();

        return view('customer.profile', compact(
            'user',
            'totalOrders',
            'completedOrders',
            'processingOrders',
            'cancelledOrders'
        ));
    }

    /**
     * Update customer profile.
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
        ]);

        $user->update($validated);

        return redirect()->route('customer.profile')->with('success', 'Profil berhasil diperbarui.');
    }

    /**
     * Menampilkan halaman / form lupa password.
     */
    public function showForgotPassword()
    {
        return view('auth.forgot-password');
    }

    /**
     * Memproses perubahan password baru berdasarkan email.
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'password' => 'required|string|min:6|confirmed',
        ], [
            'email.exists' => 'Email tidak terdaftar di sistem.',
            'password.min' => 'Password minimal harus 6 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        $user = User::where('email', $request->email)->first();
        $user->password = Hash::make($request->password);
        $user->save();

        return redirect()->route('login')->with('message', 'Password berhasil diubah! Silakan masuk dengan password baru.');
    }

    /**
     * Helper to redirect authenticated users based on their role.
     */
    protected function redirectUser()
    {
        $user = Auth::user();
        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }
        return redirect()->route('customer.dashboard');
    }
}