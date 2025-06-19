<?php

namespace App\Http\Controllers;

use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Session;
use App\Http\Middleware\UserAccess;
use App\Models\User;
use App\Models\ActivityLog;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{

    use AuthenticatesUsers;
    public function __construct()
    {
        $this->middleware('auth')->only(['dashboard', 'showSettings', 'activityLog']); // Pastikan pengguna terautentikasi untuk metode ini
        $this->middleware(UserAccess::class . ':1')->only(['activityLog']); // Hanya admin yang dapat mengakses activity log
    }

    public function index()
    {
        return view('auth.login'); // Pastikan path ini sesuai
    }

    public function login(Request $request): RedirectResponse
    {
        $input = $request->all();

        // Validasi input
        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        // Coba untuk login
        if (Auth::attempt(['email' => $input['email'], 'password' => $input['password']])) {
            // Ambil pengguna yang terautentikasi
            $user = Auth::user();

            // Simpan tipe pengguna ke dalam sesi
            session(['user_type' => $user->type]); // Pastikan 'type' adalah kolom yang benar

            // Debugging: Cek apakah sesi disimpan
            Log::info('User type stored in session: ' . session('user_type'));

            // Tentukan subject_type berdasarkan type
            $subjectType = match($user->type) {
                'admin' => 'Admin',
                'staff' => 'Staff',
                default => 'User'
            };

            // Catat aktivitas login
            if ($user->type !== 'admin') {
                ActivityLog::create([
                    'user_id' => $user->id,
                    'description' => 'User logged in',
                    'subject_type' => $subjectType, // Menggunakan subject_type yang sesuai
                    'subject_id' => $user->id,
                    'causer_id' => $user->id, // Menggunakan ID pengguna yang login
                    'properties' => json_encode(['ip_address' => $request->ip()]), // Menyimpan alamat IP
                ]);
            }
            // Redirect ke halaman dashboard
            return redirect()->intended(route('dashboard.index'))->withSuccess('Signed in');
        } else {
            // Jika login gagal, kembalikan error
            return redirect()->back()->withErrors(['email' => 'Invalid email or password.']);
        }
    }

    public function registration()
    {
        return view('auth.register'); // Pastikan path ini sesuai
    }

    public function customRegistration(Request $request)
    {
        // Validasi input
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->all();
        $data['type'] = 0; // Set default type to 0

        if ($request->hasFile('foto')) {
            $fotoPath = $request->file('foto')->store('profiles', 'public');
            $data['foto'] = $fotoPath;
        }

        $this->create($data);

        // Tentukan subject_type berdasarkan tipe pengguna
        $subjectType = match($data['type']) {
            0 => 'User',
            1 => 'Admin',
            2 => 'Staff',
            3 => 'Kepala Dinas',
            default => 'User'
        };

        // Catat aktivitas registrasi
        ActivityLog::create([
            'user_id' => User::where('email', $data['email'])->first()->id,
            'description' => 'User registered',
            'subject_type' => $subjectType,
            'subject_id' => User::where('email', $data['email'])->first()->id,
            'causer_id' => null,
            'properties' => json_encode(['ip_address' => $request->ip()]),
        ]);

        return redirect()->route('dashboard.index')->withSuccess('You have signed-in');
    }

    public function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'foto' => $data['foto'] ?? null,
            'type' => $data['type'],
        ]);
    }

    public function dashboard()
    {
        if (Auth::check()) {
            return view('dashboard'); // Pastikan path ini sesuai
        }
        return redirect("login")->withError('You are not allowed to access');
    }

    // Menampilkan form reset password
    public function showPasswordResetForm()
    {
        return view('auth.password_reset'); // Pastikan path ini sesuai
    }

    public function updatePassword(Request $request)
    {
        // Validasi input
        $request->validate([
            'full_name' => 'required|string',
            'new_password' => 'required|min:6|confirmed',
        ]);

        // Cari pengguna berdasarkan nama lengkap
        $user = User::where('name', $request->full_name)->first();

        // Cek apakah pengguna ditemukan
        if (!$user) {
            return back()->withErrors(['full_name' => 'User  not found.']);
        }

        // Update password
        $user->password = Hash::make($request->new_password);

        // Simpan perubahan ke database
        if (!$user->save()) {
            return back()->withErrors(['message' => 'Failed to update password.']);
        }

        // Tentukan subject_type berdasarkan role
        $subjectType = ($user->role === 'admin') ? 'Admin' : 'User ';

        // Catat aktivitas reset password
        ActivityLog::create([
            'user_id' => $user->id,
            'description' => 'User  reset their password.',
            'subject_type' => $subjectType, // Menggunakan subject_type yang sesuai
            'subject_id' => $user->id,
            'causer_id' => $user->id, // Menggunakan ID pengguna yang mereset password
            'properties' => json_encode(['ip_address' => request()->ip()]), // Menyimpan alamat IP
        ]);

        return redirect()->route('login')->withSuccess('Password updated successfully. You can now log in with your new password.');
    }

    public function showSettings()
    {
        // Pastikan pengguna terautentikasi
        if (!Auth::check()) {
            return redirect()->route('login')->withErrors(['message' => 'You need to be logged in to access settings.']);
        }

        $user = Auth::user();
        return view('auth.settings', compact('user')); // Pastikan path ini sesuai
    }

    public function updateSettings(Request $request)
    {
        // Pastikan pengguna terautentikasi
        if (!Auth::check()) {
            return redirect()->route('login')->withErrors(['message' => 'You need to be logged in to update settings.']);
        }

        $user = Auth::user();

        // Debugging: Cek tipe dari $user
        if (!($user instanceof User)) {
            return back()->withErrors(['message' => 'User  not found or not an instance of User model.']);
        }

        // Validasi input
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Validasi foto
            'password' => 'nullable|string|min:8|confirmed', // Validasi password (opsional)
        ]);

        // Update informasi pengguna
        $user->name = $request->name;
        $user->email = $request->email;

        // Update password jika ada input baru
        if ($request->filled('password')) {
            $user->password = bcrypt($request->password); // Hash password
        }

        // Proses upload foto jika ada
        if ($request->hasFile('foto')) {
            // Hapus foto lama jika ada
            if ($user->foto && Storage::disk('public')->exists($user->foto)) {
                Storage::disk('public')->delete($user->foto);
            }

            // Simpan foto baru
            $fotoPath = $request->file('foto')->store('profiles', 'public');
            $user->foto = $fotoPath; // Simpan path foto ke user
        }

        // Simpan perubahan ke database
        try {
            $user->save();
        } catch (\Exception $e) {
            return back()->withErrors(['message' => 'Failed to update settings: ' . $e->getMessage()]);
        }

        // Tentukan subject_type berdasarkan role
        $subjectType = ($user->role === 'admin') ? 'Admin' : 'User  ';

        // Catat aktivitas update settings
        ActivityLog::create([
            'user_id' => $user->id,
            'description' => 'User  updated settings',
            'subject_type' => $subjectType, // Menggunakan subject_type yang sesuai
            'subject_id' => $user->id,
            'causer_id' => $user->id,
            'properties' => json_encode(['ip_address' => $request->ip()]),
        ]);

        return redirect()->route('auth.settings')->withSuccess('Settings updated successfully.');
    }

    public function activityLog()
    {
        if (!Auth::check()) {
            return redirect()->route('login')->withErrors(['message' => 'You need to be logged in to view activity log.']);
        }

        $logs = ActivityLog::where('user_id', Auth::id())->orderBy('created_at', 'desc')->get();
        return view('auth.activity_log', compact('logs')); // Pastikan path ini sesuai
    }

    public function signOut(Request $request)
    {
        Auth::logout(); // Logout pengguna
        $request->session()->flush(); // Hapus semua data sesi
        return redirect()->route('home')->withSuccess('You have logged out successfully.'); // Redirect ke halaman login
    }
}
