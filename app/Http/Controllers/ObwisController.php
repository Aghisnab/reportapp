<?php

namespace App\Http\Controllers;

use App\Models\ObwisModel; // Pastikan Anda sudah membuat model ini
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;

class ObwisController extends Controller
{
    public function index()
    {
        $obwis = ObwisModel::all();
        return view('obwis.index', compact('obwis'));
    }

    public function create(): View
    {
        return view('obwis.create');
    }

    public function store(Request $request)
    {
        // Validasi input
        $validated = $request->validate([
            'obwis_id' => 'required|string|max:11',
            'nama_obwis' => 'required|string|max:255',
            'cp' => 'nullable|string|max:255', // Ganti tanggal_buka menjadi cp
            'alamat' => 'required|string',
            'maps' => 'nullable|string',
            'gambar' => 'nullable|string|max:2048', // Ubah validasi gambar menjadi string untuk URL
            'increment_status' => 'nullable|boolean', // Checkbox for incrementing status
        ]);

        // Memproses URL gambar jika ada
        if ($request->input('gambar')) {
            $url = trim($request->input('gambar'));
            $validated['gambar'] = $url;
        }

        // Simpan objek wisata dan simpan ke variabel $obwis
        $obwis = ObwisModel::create($validated);

        // Increment status if checkbox is checked
        if ($request->input('increment_status')) {
            $obwis->increment('status'); // Increment the status column
        }

        // Catat aktivitas
        if (Auth::user()->type !== 'admin') {
            $this->logActivity('added a new obwis', $obwis->nama_obwis, 'ObwisModel', $obwis->id);
        }

        return redirect()->route('obwis.index')->with('success', 'Objek Wisata berhasil dibuat!');
    }

    public function show($obwis_id)
    {
        $obwis = ObwisModel::findOrFail($obwis_id);
        return view('obwis.show', compact('obwis'));
    }

    public function edit($obwis_id)
    {
        $obwis = ObwisModel::findOrFail($obwis_id);

        // Cek role pengguna
        $userType = Auth::user()->type; // Assuming 'type' is a field in your users table
        //Log::info('User  type: ' . $userType); // Log the user type

        // Cek userType dan tampilkan view sesuai peran
        if (in_array($userType, ['admin', 'staff'])) {
            return view('obwis.edit', compact('obwis'));
        } else { // Pengguna biasa
            return view('obwis.edituser', compact('obwis'));
        }
    }

    public function update(Request $request, $id)
    {
        Log::info('Update method called with data: ', $request->all());

        $validatedData = $request->validate([
            'obwis_id' => 'required|string|max:11',
            'nama_obwis' => 'required|string|max:255',
            'cp' => 'nullable|string|max:255',
            'alamat' => 'required|string',
            'maps' => 'nullable|string',
            'gambar' => 'nullable|string|max:2048', // Ubah validasi gambar menjadi string untuk URL
            'increment_status' => 'nullable|boolean', // Checkbox for incrementing status
        ]);

        // Memproses URL gambar jika ada
        if ($request->input('gambar')) {
            $url = trim($request->input('gambar'));
            $validatedData['gambar'] = $url;
        }

        $obwis = ObwisModel::findOrFail($id);

        $obwis->update($validatedData);

        // Increment status if checkbox is checked
        if ($request->input('increment_status')) {
            $obwis->increment('status'); // Increment the status column
        }

        if (Auth::user()->type !== 'admin') {
        // Catat aktivitas
            $this->logActivity('updated obwis', $obwis->nama_obwis, 'ObwisModel', $obwis->id);
        }
        return redirect()->route('obwis.index')->with('success', 'Objek Wisata berhasil diperbarui');
    }

    public function destroy($obwis_id)
    {
        $obwis = ObwisModel::findOrFail($obwis_id);

        // Hapus objek wisata tanpa menghapus gambar dari storage karena gambar hanya berupa URL
        $obwis->delete();

        if (Auth::user()->type !== 'admin') {
            // Catat aktivitas penghapusan
            $this->logActivity('deleted obwis', $obwis->nama_obwis, 'ObwisModel', $obwis->id);
        }
        return redirect()->route('obwis.index')->with('success', 'Objek Wisata berhasil dihapus!');
    }

    public function search(Request $request)
    {
        $query = $request->input('query');
        $obwis = ObwisModel::where('obwis_id', 'LIKE', "%{$query}%")
                            ->orWhere('nama_obwis', 'LIKE', "%{$query}%")
                            ->limit(10)->get();

        return response()->json($obwis);
    }

    public function showReport()
    {
        // Ambil data dari session
        $obwis = session('obwis', collect()); // Mengambil data obwis dari session
        $alamat = session('alamat'); // Ambil alamat dari session

        // Kembalikan tampilan laporan
        return view('obwis.report', compact('obwis', 'alamat'));
    }

    public function generateReport(Request $request)
    {
        // Validasi input
        $request->validate([
            'alamat' => 'nullable|string', // Validasi alamat
        ]);

        $alamat = $request->alamat; // Ambil alamat dari request

        // Ambil data obwis berdasarkan filter alamat
        $obwis = ObwisModel::when($alamat, function ($query) use ($alamat) {
                return $query->where('alamat', 'LIKE', '%' . $alamat . '%'); // Filter berdasarkan alamat
            })
            ->get();

        // Simpan data ke session untuk ditampilkan di view
        session(['obwis' => $obwis, 'alamat' => $alamat]);

            // Ambil pengguna yang sedang login
        $user = Auth::user();

        // Tentukan subjectType berdasarkan tipe pengguna
        $subjectType = match ($user->type) {
            'admin' => 'admin',
            'staff' => 'staff',
            default => 'user'
        };

        if (Auth::user()->type !== 'admin') {
            // Catat aktivitas generate report
            $this->logActivity('generate objek wisata report', 'Objek Wisata Report for address: ' . $alamat, $subjectType, $user->id);
        }
        // Redirect ke metode showReport
        return redirect()->route('obwis.report'); // Pastikan rute ini ada
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
