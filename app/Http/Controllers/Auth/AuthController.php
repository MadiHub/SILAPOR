<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    public function index()
    {
        return view('Auth.index');
    }

    public function register(Request $request)
    {
                // dd($request->all());

    $validator = Validator::make($request->all(), [
    'name' => 'required|string|max:255',
    'email' => 'required|email|unique:users,email',
    'phone' => 'required|string|unique:users,phone',
    'password' => 'required|string|min:8|confirmed',
    ], [
    'name.required' => 'Nama wajib diisi',
    'email.required' => 'Email wajib diisi',
    'email.unique' => 'Email sudah terdaftar',
    'phone.required' => 'Nomor HP wajib diisi',
    'phone.unique' => 'Nomor HP sudah terdaftar',
    'password.required' => 'Password wajib diisi',
    'password.min' => 'Password minimal 8 karakter',
    'password.confirmed' => 'Konfirmasi password tidak cocok',
    ]);


    if ($validator->fails()) {
        return back()
            ->withErrors($validator)
            ->withInput()
            ->with('form_type', 'register'); 
    }

    $user = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'phone' => $request->phone,
        'password' => Hash::make($request->password),
        'role' => 'warga',
    ]);

    Auth::login($user);

    return redirect()->route('home.index')->with('success', 'Akun berhasil dibuat!');


    }

    public function login(Request $request)
    {
        // dd($request->all());
        $rules = [
            'password' => 'required|string',
        ];

        if ($request->filled('email')) {
            $rules['email'] = 'required|email';
        } elseif ($request->filled('phone')) {
            $rules['phone'] = 'required|string';
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput()
                ->with('form_type', 'login');
        }

        if ($request->filled('email')) {
            $credentials = [
                'email' => $request->email,
                'password' => $request->password,
            ];
        } else {
            $credentials = [
                'phone' => $request->phone,
                'password' => $request->password,
            ];
        }

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            $user = Auth::user();

            if ($user->role === 'admin') {
                return redirect()->route('admin.dashboard');
            }

            if ($user->role === 'pemda') {
                return redirect()->route('pemda.dashboard');
            }

            return redirect()->route('home.index')->with('success', 'Berhasil login!');
        }

        return back()->withErrors([
            'login' => 'Email/No HP atau password salah.',
        ])->withInput();
    }

    public function google_redirect()
    {
        return Socialite::driver('google')->stateless()->redirect();
    }

    public function google_callback()
    {
        try {
            $googleUser = Socialite::driver('google')->stateless()->user();

            $user = User::where('google_id', $googleUser->getId())->first();

            if (!$user) {
                $user = User::where('email', $googleUser->getEmail())->first();


                if ($user) {
                    $user->update([
                        'google_id' => $googleUser->getId(),
                        'avatar' =>  $googleUser->getAvatar()
                    ]);
                } else {
                   $user = User::create([
                        'name' => $googleUser->getName(),
                        'email' => $googleUser->getEmail(),
                        'role' => 'warga',
                        'google_id' => $googleUser->getId(),
                        'avatar' => $googleUser->getAvatar(),
                        'password' => null,
                    ]);
                }
            }

            Auth::login($user);

            return redirect()->route('home.index')->with('success', 'Berhasil login!');


        } catch (\Exception $e) {
            dd($e);
            return redirect('/login')->with('error', 'Gagal login dengan Google.');
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate(); 
        $request->session()->regenerateToken(); 

        return redirect('/'); 
    }

    public function profile() {
        $user = Auth::user();

        if(!$user) {
            abort(404);
        }

        return view('Auth.v_profile.index', compact('user'));
    }

    public function profile_update(Request $request)
    {
        $user = Auth::user();

        if(!$user) {
            abort(404);
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'no_hp' => ['nullable', 'string', 'max:20'],
            'current_password' => ['nullable', 'string'],
            'password' => ['nullable', 'string', 'confirmed', 'min:6'],
        ]);

        $user->name = $validated['name'];
        $user->no_hp = $validated['no_hp'] ?? null;

        if ($request->filled('current_password') || $request->filled('password')) {
            if (!Hash::check($request->current_password, $user->password)) {
                return back()->withErrors(['current_password' => 'Password saat ini tidak sesuai.']);
            }

            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        return redirect()->back()->with('success', 'Profil berhasil diperbarui.');
    }
    
}