<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EventModel;
use App\Models\PlanModel;
use App\Models\ObwisModel;
use App\Enums\MonthEnum;

class HomeController extends Controller
{
    public function index()
    {
        // Mengambil satu data objek wisata dengan status tertinggi
        $latestObwis = ObwisModel::select('id', 'nama_obwis', 'alamat', 'maps', 'gambar', 'status')
            ->orderBy('status', 'desc')->first();

        // Menghitung total data
        $totalEvents = EventModel::count();
        $totalPlans = PlanModel::count(); // Menghitung total data Plan
        $totalEventsThisYear = EventModel::whereYear('tanggal_mulai', date('Y'))->count();
        $totalObwis = ObwisModel::count();


        // Mengambil 10 data event terbaru
        $events = EventModel::orderBy('tanggal_mulai', 'desc')->take(5)->get();

        // Mengambil 10 data objek wisata dengan status tertinggi
        $obwis = ObwisModel::orderBy('status', 'desc')->take(5)->get();

        // Mengambil 10 data plan terbaru berdasarkan tanggal mulai terdekat
        $plans = PlanModel::orderBy('tanggal_mulai', 'asc')->take(5)->get();

        return view('home', compact('latestObwis', 'totalEvents', 'totalPlans', 'totalEventsThisYear', 'totalObwis', 'events', 'obwis', 'plans'));
    }
}
