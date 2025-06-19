<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\ActivityLog;

class ActivityLogController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // Menampilkan log aktivitas
    public function activityLog()
    {
        // Pastikan pengguna terautentikasi
        if (!Auth::check()) {
            return redirect()->route('login')->withErrors(['message' => 'Anda perlu login untuk melihat log aktivitas.']);
        }

        // Ambil pengguna yang sedang login
        $user = Auth::user();

        // Ambil log aktivitas berdasarkan tipe pengguna
        switch ($user->type) {
            case 'admin':
                // Jika pengguna adalah admin, ambil semua log aktivitas
                $logs = ActivityLog::orderBy('created_at', 'desc')->get();
                break;
            case 'staff':
            case 'user':
            default:
                // Jika pengguna adalah user atau tipe lainnya, ambil log hanya untuk pengguna yang sedang login
                $logs = ActivityLog::where('user_id', $user->id)->orderBy('created_at', 'desc')->get();
                break;
        }

        // Kirim data log ke view
        return view('auth.activity_log', compact('logs'));
    }

    public function destroy(Request $request)
    {
        $ids = $request->input('ids');

        if ($ids) {
            // Hapus log berdasarkan ID
            ActivityLog::destroy($ids);

            Log::info('Activity logs deleted:', ['ids' => $ids]);

            // Redirect kembali ke activity log dengan pesan sukses
            return response()->json(['success' => true, 'redirect' => route('activity.log')]);
        }

        return response()->json(['success' => false, 'error' => 'Tidak ada log yang dipilih.'], 400);
    }
}
