<?php

namespace App\Http\Controllers;

use App\Models\DetailEventModel;
use App\Models\ActivityLog;
use App\Models\EventModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class DetailEventController extends Controller
{
    public function index($event_id)
    {
        $event = EventModel::findOrFail($event_id);
        $details = DetailEventModel::where('event_id', $event_id)->get();

        return view('detailevent.index', compact('event', 'details'));
    }

    public function create($event_id)
    {
        $event = EventModel::findOrFail($event_id);
        return view('detailevent.create', compact('event'));
    }

    public function store(Request $request, $event_id)
    {
        $validated = $request->validate([
            'hari_ke' => 'required|integer',
            'tanggal' => 'required|date',
            'rangkaian_acara' => 'required|string|max:255',
            'dokumentasi1' => 'nullable|string',
            'dokumentasi2' => 'nullable|string',
        ]);

        if ($request->input('dokumentasi1')) {
            $urls = array_map('trim', explode(',', $request->input('dokumentasi1')));
            $validated['dokumentasi1'] = json_encode($urls);
        }

        if ($request->input('dokumentasi2')) {
            $urls = array_map('trim', explode(',', $request->input('dokumentasi2')));
            $validated['dokumentasi2'] = json_encode($urls);
        }

        $validated['event_id'] = $event_id;

        $detail = DetailEventModel::create($validated);

        if (Auth::user()->type !== 'admin') {
            $this->logActivity('added a new event detail', $detail->rangkaian_acara, 'event', $detail->id);
        }
        return redirect()->route('events.detailevent.index', $event_id)->with('success', 'Detail event berhasil ditambahkan!');
    }

    public function edit($event_id, $id)
    {
        $detail = DetailEventModel::findOrFail($id);
        $event = EventModel::findOrFail($event_id);
        return view('detailevent.edit', compact('detail', 'event'));
    }

    public function update(Request $request, $event_id, $id)
    {
        $validated = $request->validate([
            'hari_ke' => 'required|integer',
            'tanggal' => 'required|date',
            'rangkaian_acara' => 'required|string|max:255',
            'dokumentasi1' => 'nullable|string',
            'dokumentasi2' => 'nullable|string',
        ]);

        $detail = DetailEventModel::findOrFail($id);

        if ($request->input('dokumentasi1')) {
            $urls = array_map('trim', explode(',', $request->input('dokumentasi1')));
            $validated['dokumentasi1'] = json_encode($urls);
        }

        if ($request->input('dokumentasi2')) {
            $urls = array_map('trim', explode(',', $request->input('dokumentasi2')));
            $validated['dokumentasi2'] = json_encode($urls);
        }

        $detail->update($validated);

        if (Auth::user()->type !== 'admin') {
            $this->logActivity('updated event detail', $detail->rangkaian_acara, 'event', $detail->id);
        }
        return redirect()->route('events.detailevent.index', $event_id)->with('success', 'Detail event berhasil diperbarui!');
    }

    public function destroy($event_id, $id)
    {
        $detail = DetailEventModel::findOrFail($id);
        $detail->delete();

        if (Auth::user()->type !== 'admin') {
            $this->logActivity('deleted event detail', $detail->rangkaian_acara, 'event', $detail->id);
        }
        return redirect()->route('events.detailevent.index', $event_id)->with('success', 'Detail event berhasil dihapus!');
    }

    public function detailPrint($event_id)
    {
        $event = EventModel::findOrFail($event_id);
        $details = DetailEventModel::where('event_id', $event_id)->get();

        return view('detailevent.print', compact('event', 'details'));
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
