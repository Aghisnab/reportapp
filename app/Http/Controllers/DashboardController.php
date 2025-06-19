<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Log;
use App\Models\EventModel;
use App\Models\PlanModel;
use App\Models\ObwisModel;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index(): View
    {
        // Cek apakah pengguna terautentikasi
        if (!Auth::check()) {
            Log::info('User is not authenticated.');
            return redirect()->route('login')->withErrors(['message' => 'You need to be logged in to access the dashboard.']);
        }

        // Cek role pengguna
        $userType = Auth::user()->type;
        Log::info('User type: ' . $userType); // Tambahkan log ini

        // Mengambil total plans
        $totalPlans = PlanModel::count();

        // Mengambil total event
        $totalEvents = EventModel::count();

        // Mengambil total objek wisata
        $totalObwis = ObwisModel::count();

        // Mengambil total event per tahun ini
        $totalEventsThisYear = EventModel::whereYear('tanggal_mulai', date('Y'))->count();

        // Mengambil satu data event terbaru
        $latestEvent = EventModel::select('id', 'nama_event', 'tanggal_mulai', 'tanggal_selesai', 'alamat', 'gambar')
            ->orderBy('tanggal_mulai', 'desc')->first();

        // Mengambil satu data objek wisata dengan status tertinggi
        $latestObwis = ObwisModel::select('id', 'nama_obwis', 'alamat', 'maps', 'gambar', 'status')
            ->orderBy('status', 'desc') // Order by status in descending order
            ->first(); // Get the first entry with the highest status

        // Tampilkan dashboard
        return view('dashboard', compact('totalPlans', 'totalEvents', 'totalObwis', 'totalEventsThisYear', 'latestEvent', 'latestObwis'));
    }
}
