<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\AuthenticationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    protected $authService;

    public function __construct(AuthenticationService $authService)
    {
        $this->authService = $authService;
    }

    // Login page
    public function login()
    {
        if (Auth::check()) {
            return redirect()->intended($this->getRedirectPath());
        }
        return view('auth.login');
    }

    // Process login
    public function authenticate(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:6',
            'remember' => 'nullable|boolean',
        ]);

        $user = $this->authService->authenticate($validated['email'], $validated['password']);

        if (!$user) {
            return back()->withInput($request->only('email'))->withErrors([
                'email' => 'Invalid email or password.',
            ]);
        }

        Auth::login($user, $validated['remember'] ?? false);
        
        $request->session()->regenerate();

        return redirect()->intended($this->getRedirectPath());
    }

    // Registration page
    public function register()
    {
        if (Auth::check()) {
            return redirect()->intended($this->getRedirectPath());
        }
        return view('auth.register');
    }

    // Process registration
    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'nullable|string|unique:users,phone',
            'password' => 'required|string|min:8|confirmed',
            'agree_terms' => 'required|accepted',
        ]);

        try {
            $user = $this->authService->registerUser([
                'role' => 'student',
                'first_name' => $validated['first_name'],
                'last_name' => $validated['last_name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'] ?? null,
                'password' => $validated['password'],
                'country' => $request->input('country', 'Nigeria'),
            ]);

            Auth::login($user);
            
            return redirect()->route('student.profile')->with('success', 'Account created successfully! Please complete your profile.');
        } catch (\Exception $e) {
            return back()->withInput($request->only('first_name', 'last_name', 'email', 'phone'))
                ->with('error', 'Registration failed. Please try again.');
        }
    }

    // Forgot password page
    public function forgotPassword()
    {
        return view('auth.forgot-password');
    }

    // Send password reset link
    public function sendResetLink(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        $status = Password::sendResetLink($validated);

        if ($status === Password::RESET_LINK_SENT) {
            return back()->with('success', 'Password reset link sent to your email.');
        }

        return back()->with('error', 'Unable to send password reset link.');
    }

    // Reset password page
    public function resetPassword(Request $request, $token)
    {
        return view('auth.reset-password', ['token' => $token]);
    }

    // Update password
    public function updatePassword(Request $request)
    {
        $validated = $request->validate([
            'token' => 'required|string',
            'email' => 'required|email|exists:users,email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $status = Password::reset($validated, function (User $user, string $password) {
            $user->forceFill([
                'password' => Hash::make($password),
                'remember_token' => Str::random(60),
            ])->save();
        });

        if ($status === Password::PASSWORD_RESET) {
            return redirect()->route('login')->with('success', 'Password reset successfully. Please log in.');
        }

        return back()->with('error', 'Password reset failed. Please try again.');
    }

    // Logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')->with('success', 'Logged out successfully.');
    }

    // Helper function to determine redirect path based on role
    private function getRedirectPath()
    {
        $user = Auth::user();

        if ($user->isStudent()) {
            return route('student.dashboard');
        } elseif ($user->isAdmin()) {
            return route('admin.dashboard');
        } elseif ($user->isStaff()) {
            return route('staff.dashboard');
        }

        return route('home');
    }
}
