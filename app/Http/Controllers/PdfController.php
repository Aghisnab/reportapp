<?php

namespace App\Http\Controllers;

use Dompdf\Dompdf;
use Dompdf\Options;
use Illuminate\Http\Request;
use App\Models\EventModel;
use App\Models\DetailEventModel;
use App\Models\ObwisModel;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;

class PdfController extends Controller
{
    public function eventPdf(Request $request)
    {
        // Validasi input tanggal
        $request->validate([
            'txtTglAwal' => 'required|date',
            'txtTglAkhir' => 'nullable|date|after_or_equal:txtTglAwal',
        ]);

        $awal = $request->txtTglAwal;
        $akhir = $request->txtTglAkhir ?? $awal; // Jika txtTglAkhir tidak diisi, gunakan txtTglAwal

        // Ambil data events berdasarkan filter tanggal
        $events = EventModel::whereBetween('tanggal_mulai', [$awal, $akhir])
            ->with('notes')
            ->get();

        // Buat instance Dompdf
        $pdf = new Dompdf();

        // Load HTML content (Blade view)
        $html = view('kegiatan.pdf', compact('events', 'awal', 'akhir'))->render();
        $pdf->loadHtml($html);

        // Set options for PDF
        $options = new Options();
        $options->set('defaultFont', 'Arial');
        $pdf->setOptions($options);

        // Render PDF
        $pdf->render();

        // Ambil pengguna yang sedang login
        $user = Auth::user();

        // Tentukan subjectType berdasarkan tipe pengguna
        $subjectType = match ($user->type) {
            'admin' => 'admin',
            'staff' => 'staff',
            default => 'user'
        };

        $subjectId = $user->id;

        // Panggil logActivity
        $this->logActivity('generated event report', 'Event report from ' . $awal . ' to ' . $akhir, $subjectType, $subjectId);

        // Stream the generated PDF to the browser
        return $pdf->stream('laporan_event.pdf');
    }

    public function obwisPdf(Request $request)
    {

        // Ambil alamat dari request
        $alamat = $request->input('alamat');

        // Ambil data obwis berdasarkan filter alamat
        $obwis = ObwisModel::when($alamat, function ($query) use ($alamat) {
                return $query->where('alamat', 'LIKE', '%' . $alamat . '%');
            })
            ->get();

        // Buat instance Dompdf
        $pdf = new Dompdf();

        // Load HTML content (Blade view)
        $html = view('obwis.pdf', compact('obwis', 'alamat'))->render();
        // Ganti path gambar dengan path yang benar
        $html = $this->replaceImagePath($html, $obwis);
        $pdf->loadHtml($html);

        // Set options for PDF
        $options = new Options();
        $options->set('isRemoteEnabled', true);
        $options->set('defaultFont', 'Arial');
        $pdf->setOptions($options);

        // Render PDF
        $pdf->render();

        // Ambil pengguna yang sedang login
        $user = Auth::user();

        // Tentukan subjectType berdasarkan tipe pengguna
        $subjectType = match ($user->type) {
            'admin' => 'admin',
            'staff' => 'staff',
            default => 'user'
        };

        $subjectId = $user->id;

        // Panggil logActivity
        $this->logActivity('generated obwis report', 'Obwis report for address: ' . $alamat, $subjectType, $subjectId);

        // Stream the generated PDF to the browser
        return $pdf->stream('laporan_obwis.pdf');
    }

    private function replaceImagePath($html, $obwis)
    {
        // Replace the image paths for obwis data
        foreach ($obwis as $item) {
            if ($item->gambar) {
                // Decode the JSON to get the URL
                $imagePath = json_decode($item->gambar, true); // Assuming it contains a single URL as a JSON string
                if ($imagePath) {
                    // Replace the placeholder with the actual image tag
                    $html = str_replace($item->gambar, '<img src="'. $imagePath .'" alt="Gambar Obwis" class="img-thumbnail" style="max-width: 150px;">', $html);
                }
            }
        }
        return $html;
    }


    protected function logActivity($action, $title, $subjectType, $subjectId)
    {
        // Tentukan kata yang akan digunakan berdasarkan subjectType
        $userType = match ($subjectType) {
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

    public function printDetail($eventId)
    {
        // Ambil data event dan detail event berdasarkan ID event
        $event = EventModel::findOrFail($eventId);
        $details = DetailEventModel::where('event_id', $eventId)->get();

        // Buat instance Dompdf
        $pdf = new Dompdf();

        // Load HTML content (Blade view)
        $html = view('detailevent.pdf', compact('event', 'details'))->render();

        // Ganti path gambar dengan path yang benar
        $html = $this->replaceImagePaths($html, $details);
        $pdf->loadHtml($html);

        // Set options for PDF
        $options = new Options();
        $options->set('isRemoteEnabled', true);
        $options->set('defaultFont', 'Arial');
        $pdf->setOptions($options);

        // Render PDF
        $pdf->render();

        // Log aktivitas pengguna yang mencetak
        $user = Auth::user();
        $subjectType = match ($user->type) {
            'admin' => 'admin',
            'staff' => 'staff',
            default => 'user'
        };

        $this->logActivity('printed event details', 'Printed event details for ' . $event->nama_event, $subjectType, $user->id);

        // Bersihkan nama event dari karakter yang tidak diizinkan dalam nama file
        $safeEventName = preg_replace('/[^a-zA-Z0-9_-]/', '_', $event->nama_event);

        // Stream the generated PDF to the browser dengan nama event
        return $pdf->stream($safeEventName . '.pdf');
    }

    private function replaceImagePaths($html, $details)
    {
        // Loop through the detail event data to replace image paths
        foreach ($details as $detail) {
            if ($detail->dokumentasi) {
                $urls = json_decode($detail->dokumentasi, true);
                if (is_array($urls)) {
                    foreach ($urls as $url) {
                        $imagePath = $url; // Assuming $url is already an absolute path
                        $html = str_replace($url, $imagePath, $html);
                    }
                }
            }
        }
        return $html;
    }
}

