<?php

namespace App\Http\Controllers;

use App\Enums\MonthEnum;
use App\Models\EventModel;
use App\Models\NotesModel;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage; // Tambahkan ini
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;


class EventController extends Controller
{
    public function index()
    {
        $events = EventModel::with('notes')->get();
        return view('kegiatan.index', compact('events'));
    }

    //kalender
    public function calendarData()
    {
        $events = EventModel::with('notes')->get();

        $data = $events->map(function ($event) {
            return [
                'id' => $event->id,
                'title' => $event->nama_event,
                'start' => $event->tanggal_mulai,
                'end' => $event->tanggal_selesai,
                'color' => $event->notes->isNotEmpty() ? '#ff9f89' : '#3788d8',
                'notes' => $event->notes->map(function ($note) {
                    return [
                        'tanggal_catatan' => $note->tanggal_catatan,
                        'isi_catatan' => $note->isi_catatan,
                    ];
                }),
            ];
        });

        return response()->json($data);
    }

    public function search(Request $request)
    {
        $query = $request->get('query');

        // Mencari event berdasarkan nama
        $events = EventModel::where('nama_event', 'LIKE', '%' . $query . '%')->get();

        return response()->json($events);
    }

    public function create(): view
    {
        return view('kegiatan.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'event_id' => 'required|string',
            'nama_event' => 'required|string|max:255',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date',
            'bulan_event' => 'required|string', // Validasi enum
            'alamat' => 'required|string',
            'deskripsi' => 'nullable|string',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'artikel' => 'nullable|url', // Validasi URL untuk artikel
        ]);

        // Validasi manual untuk bulan_event
        $validMonths = array_map(fn($month) => $month->value, MonthEnum::cases());

        if (!in_array($validated['bulan_event'], $validMonths)) {
            return redirect()->back()->withErrors(['bulan_event' => 'The selected value is invalid.'])->withInput();
        }

        if ($request->hasFile('gambar')) {
            $gambarPath = $request->file('gambar')->store('images/events', 'public');
            $validated['gambar'] = $gambarPath;
        }

        $event = EventModel::create($validated);

        // Cek apakah ada note yang sesuai dengan tanggal_mulai atau tanggal_selesai
        $note = NotesModel::where('tanggal_catatan', $validated['tanggal_mulai'])
            ->orWhere('tanggal_catatan', $validated['tanggal_selesai'])
            ->first();

        if ($note) {
            $event->note_id = $note->id;
            Log::info('Setting note_id in EventController', ['note_id' => $note->id, 'event_id' => $event->id]);
            $event->save();
        }

        if (Auth::user()->type !== 'admin') {
            // Catat aktivitas
            $this->logActivity('added a new event', $event->nama_event, 'EventModel', $event->id);
        }
        return redirect()->route('kegiatan.index')->with('success', 'Event successfully created!');
    }

    public function show($id)
    {
        $event = EventModel::find($id); // Mengambil event berdasarkan ID

        if (!$event) {
            return response()->json(['error' => 'Event not found'], 404);
        }

        return response()->json($event); // Mengembalikan detail event dalam format JSON
    }

    public function edit($event_id)
    {
        $events = EventModel::findOrFail($event_id);
        $months = array_map(fn($month) => $month->value, MonthEnum::cases());
        return view('kegiatan.edit', compact('events', 'months'));
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'event_id' => 'required|string',
            'nama_event' => 'required|string|max:255',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date',
            'bulan_event' => [
                'required',
                Rule::in(array_map(fn($month) => $month->value, \App\Enums\MonthEnum::cases())), // Validate against integer enum values
            ],
            'alamat' => 'required|string',
            'deskripsi' => 'nullable|string',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'artikel' => 'nullable|url', // Validasi URL untuk artikel
        ]);

        // Validasi manual untuk bulan_event
        $validMonths = array_map(fn($month) => $month->value, MonthEnum::cases());

        if (!in_array($validatedData['bulan_event'], $validMonths)) {
            return redirect()->back()->withErrors(['bulan_event' => 'The selected value is invalid.'])->withInput();
        }

        $events = EventModel::find($id);
        if (!$events) {
            return redirect()->route('events.index')
                ->with('error', 'Event not found.');
        }

        if ($request->hasFile('gambar')) {
            if ($events->gambar && Storage::disk('public')->exists($events->gambar)) {
                Storage::disk('public')->delete($events->gambar);
            }

            $gambarPath = $request->file('gambar')->store('images/events', 'public');
            $validatedData['gambar'] = $gambarPath;
        }

        $events->update($validatedData);

        // Mencari note yang sesuai dengan tanggal_mulai atau tanggal_selesai
        $note = NotesModel::where('tanggal_catatan', $validatedData['tanggal_mulai'])
            ->orWhere('tanggal_catatan', $validatedData['tanggal_selesai'])
            ->first();

        // Jika ada note yang sesuai, simpan note_id ke event
        if ($note) {
            $events->note_id = $note->id;
            $events->save();
        }

        if (Auth::user()->type !== 'admin') {
            // Catat aktivitas
            $this->logActivity('updated event', $events->nama_event, 'EventModel', $events->id);
        }
        return redirect()->route('kegiatan.index')
            ->with('success', 'Event has been updated successfully.');
    }

    public function destroy($id)
    {
        // Find the event by ID
        $event = EventModel::findOrFail($id);

        // Check if the event has a gambar (image)
        if ($event->gambar) {
            // Ensure the file exists before attempting to delete
            $imagePath = 'public/' . $event->gambar;
            if (Storage::exists($imagePath)) {
                // Delete the image file
                Storage::delete($imagePath);
            }
        }

        // Delete related notes
        NotesModel::where('event_id', $id)->delete();

        // Delete the event record from the database
        $event->delete();

        if (Auth::user()->type !== 'admin') {
            // Catat aktivitas penghapusan
            $this->logActivity('deleted event', $event->nama_event, 'EventModel', $event->id);
        }
        // Redirect back with a success message
        return redirect()->route('kegiatan.index')->with('success', 'Event berhasil dihapus');
    }

    public function addNote(Request $request, $event_id)
    {
        // Validate the input
        $validated = $request->validate([
            'tanggal_catatan' => 'required|date',
            'isi_catatan' => 'required|string',
        ]);

        // Assuming you have a Note model that stores the notes
        $note = new NotesModel();
        $note->event_id = $event_id; // Assuming there's a relationship with the Event model
        $note->tanggal_catatan = $request->tanggal_catatan;
        $note->isi_catatan = $request->isi_catatan;
        $note->save();

        // Return a response
        return response()->json(['success' => true, 'message' => 'Note added successfully']);
    }

    public function getEventsForDate(Request $request)
    {
        // Correct the column name if necessary
        $date = $request->input('date');

        // Assuming 'tanggal_mulai' is the correct date column in your database.
        $events = EventModel::where('tanggal_mulai', $date)
            ->orWhere('tanggal_selesai', $date)
            ->select('event_id', 'nama_event', 'alamat', 'gambar', 'tanggal_mulai', 'tanggal_selesai')
            ->get();

        // Log and return events
        Log::info('Fetched events for date: ' . $date, ['events' => $events]);

        return response()->json(['events' => $events]);
    }

    public function showReport()
    {
        // Ambil data dari session
        $events = session('events', collect()); // Mengambil data events dari session
        $awal = session('awal');
        $akhir = session('akhir');

        // Kembalikan tampilan laporan
        return view('kegiatan.report', compact('events', 'awal', 'akhir'));
    }

    public function generateReport(Request $request)
    {
        // Validasi input tanggal
        $request->validate([
            'txtTglAwal' => 'required|date',
            'txtTglAkhir' => 'nullable|date|after_or_equal:txtTglAwal',
        ]);

        $awal = $request->txtTglAwal;
        $akhir = $request->txtTglAkhir;

        // Ambil data events berdasarkan filter tanggal dan load relasi note
        $events = EventModel::where('tanggal_mulai', '>=', $awal)
            ->when($akhir, function ($query) use ($akhir) {
                return $query->where('tanggal_selesai', '<=', $akhir);
            })
            ->with('note') // Jika Anda ingin menampilkan catatan (One to One)
            ->get();

        // Simpan data ke session untuk ditampilkan di view
        session(['events' => $events, 'awal' => $awal, 'akhir' => $akhir]);


        // Ambil pengguna yang sedang login
        $user = Auth::user();

        // Tentukan subjectType berdasarkan tipe pengguna
        $subjectType = match ($user->type) {
            'admin' => 'admin',
            'staff' => 'staff',
            default => 'user'
        };

        if (Auth::user()->type !== 'admin') {
            // Panggil logActivity
            $this->logActivity('menghasilkan laporan kegiatan', 'Laporan kegiatan dari ' . $awal . ' hingga ' . $akhir, $subjectType, $user->id);
        }
        // Redirect ke metode showReport
        return redirect()->route('kegiatan.report');
    }

    protected function logActivity($action, $title, $subjectType, $subjectId)
    {
        // Tentukan kata yang akan digunakan berdasarkan subjectType
        $userType = match (Auth::user()->type) {
            'admin' => 'Admin',
            'staff' => 'Staff',
            default => 'User'
        };

        ActivityLog::create([
            'user_id' => Auth::id(), // Menggunakan Auth facade untuk mendapatkan ID pengguna
            'description' => $userType . ' ' . $action . ': ' . $title,
            'subject_type' => $subjectType,
            'subject_id' => $subjectId,
            'causer_id' => Auth::id(), // Menggunakan Auth facade untuk mendapatkan ID pengguna
            'properties' => json_encode(['ip_address' => request()->ip()]),
        ]);
    }
}
