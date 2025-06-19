<?php

namespace App\Http\Controllers;

use App\Models\PlanModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Services\PlanService;
use Illuminate\View\View;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;

class PlanController extends Controller
{
    protected $planService;

    public function __construct(PlanService $planService)
    {
        $this->planService = $planService;
    }

    public function index()
    {
        $plans = PlanModel::all();
        return view('plan.index', compact('plans'));
    }

    public function create(): view
    {
        return view('plan.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'event_id' => 'required|string|unique:plans,event_id|max:11',
            'nama_event' => 'required|string|max:255',
            'tanggal_mulai' => 'nullable|date',
            'tanggal_selesai' => 'nullable|date',
            'bulan_event' => 'required|string',
            'alamat' => 'required|string',
            'deskripsi' => 'nullable|string',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'event_selesai' => 'boolean',
        ]);

        if ($request->hasFile('gambar')) {
            $gambarPath = $request->file('gambar')->store('images/plan', 'public');
            $validated['gambar'] = $gambarPath;
        }

        $plan = PlanModel::create($validated);

        if (Auth::user()->type !== 'admin') {
            $this->logActivity('created', $plan->nama_event, 'PlanModel', $plan->id);
        }
        return redirect()->route('plan.index')->with('success', 'Plan successfully created!');
    }

    public function show($id)
    {
        $plan = PlanModel::findOrFail($id);
        return view('plan.show', compact('plan'));
    }

    public function edit($id)
    {
        $plan = PlanModel::findOrFail($id);
        return view('plan.edit', compact('plan'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'event_id' => 'required|string|max:11',
            'nama_event' => 'required|string|max:255',
            'tanggal_mulai' => 'nullable|date',
            'tanggal_selesai' => 'nullable|date',
            'bulan_event' => 'required|string',
            'alamat' => 'required|string',
            'deskripsi' => 'nullable|string',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'event_selesai' => 'boolean',
        ]);

        $plan = PlanModel::findOrFail($id);

        if ($request->hasFile('gambar')) {
            if ($plan->gambar && Storage::disk('public')->exists($plan->gambar)) {
                Storage::disk('public')->delete($plan->gambar);
            }

            $gambarPath = $request->file('gambar')->store('images/plan', 'public');
            $validated['gambar'] = $gambarPath;
        } else {
            $validated['gambar'] = $plan->gambar;
        }

        $plan->update($validated);

        if (Auth::user()->type !== 'admin') {
            $this->logActivity('updated', $plan->nama_event, 'PlanModel', $plan->id);
        }
        return redirect()->route('plan.index')->with('success', 'Plan successfully updated!');
    }

    public function destroy($id)
    {
        $plan = PlanModel::findOrFail($id);

        if ($plan->gambar && Storage::disk('public')->exists($plan->gambar)) {
            Storage::disk('public')->delete($plan->gambar);
        }

        $plan->delete();

        if (Auth::user()->type !== 'admin') {
            $this->logActivity('deleted', $plan->nama_event, 'PlanModel', $plan->id);
        }
        return redirect()->route('plan.index')->with('success', 'Plan successfully deleted!');
    }

    public function moveFinishedEvents()
    {
        $this->planService->moveFinishedEvents();

        return redirect()->route('plan.index')->with('success', 'Finished events successfully moved to events table!');
    }

    public function setSelesai($id)
    {
        $plan = PlanModel::findOrFail($id);
        $plan->event_selesai = true;
        $plan->save();

        // Pindahkan event selesai ke tabel events
        $this->planService->moveFinishedEvents();
        
        $this->logActivity('set selesai', $plan->nama_event, 'PlanModel', $plan->id);

        return redirect()->route('plan.index')->with('success', 'Plan successfully marked as finished!');
    }

    protected function logActivity($action, $title, $subjectType, $subjectId)
    {
        $userType = match (Auth::user()->type) {
            'admin' => 'Admin',
            'staff' => 'Staff',
            default => 'User'
        };

        ActivityLog::create([
            'user_id' => Auth::id(),
            'description' => $userType . ' ' . $action . ': ' . $title,
            'subject_type' => $subjectType,
            'subject_id' => $subjectId,
            'causer_id' => Auth::id(),
            'properties' => json_encode(['ip_address' => request()->ip()]),
        ]);
    }
}
