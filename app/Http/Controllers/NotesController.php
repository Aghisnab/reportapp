<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Log;
use App\Models\NotesModel;
use App\Models\EventModel;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;

class NotesController extends Controller
{
    public function getNoteForDate(Request $request)
    {
        $note = NotesModel::where('tanggal_catatan', $request->date)->first();
        return response()->json(['note' => $note]);
    }

    public function addNote(Request $request)
    {
        try {
            // Simpan atau update note
            $note = NotesModel::updateOrCreate(
                ['tanggal_catatan' => $request->tanggal_catatan],
                ['isi_catatan' => $request->isi_catatan]
            );

            // Catat aktivitas
            $this->logActivity('added/updated note', $note->isi_catatan, 'NotesModel', $note->id);

            // Cari event yang memiliki tanggal_mulai atau tanggal_selesai yang sama dengan tanggal_catatan
            $event = EventModel::where('tanggal_mulai', $note->tanggal_catatan)
                ->orWhere('tanggal_selesai', $note->tanggal_catatan)
                ->first();

            // Jika event ditemukan, update kolom note_id di event tersebut
            if ($event) {
                $event->note_id = $note->id; // note_id adalah FK yang dihubungkan dengan notes
                $event->save(); // Simpan perubahan ke database
            }

            return response()->json(['success' => true, 'note' => $note]);
        } catch (\Exception $e) {
            // Log the error for debugging
            Log::error('Error adding/updating note: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json(['success' => false, 'message' => 'Internal Server Error'], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $note = NotesModel::find($id);
        if ($note) {
            $note->isi_catatan = $request->isi_catatan;
            $note->save();

            // Catat aktivitas
            $this->logActivity('updated note', $note->isi_catatan, 'NotesModel', $note->id);

            // Update event jika tanggal_catatan sudah diubah
            $event = EventModel::where('tanggal_mulai', $note->tanggal_catatan)
                ->orWhere('tanggal_selesai', $note->tanggal_catatan)
                ->first();

            if ($event) {
                $event->note_id = $note->id;
                $event->save();
            }

            return response()->json(['success' => true, 'note' => $note]);
        }
        return response()->json(['success' => false, 'message' => 'Note not found.'], 404);
    }

    public function destroy($id)
    {
        $note = NotesModel::find($id);
        if ($note) {
            $note->delete();
            // Catat aktivitas penghapusan
            $this->logActivity('deleted note', $note->isi_catatan, 'NotesModel', $note->id);
            return response()->json(['success' => true, 'message' => 'Note deleted successfully.']);
        }
        return response()->json(['success' => false, 'message' => 'Note not found.'], 404);
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
