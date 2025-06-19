<?php

namespace App\Http\Controllers;

use App\Models\EventModel;
use App\Models\NotesModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CalendarController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = EventModel::with('notes')
                ->whereDate('tanggal_mulai', '>=', $request->start)
                ->whereDate('tanggal_selesai', '<=', $request->end)
                ->get(['event_id', 'nama_event', 'tanggal_mulai', 'tanggal_selesai']);

            return response()->json($data);
        }

        return view('calendar.index');
    }

    public function ajax(Request $request)
    {
        switch ($request->type) {
            case 'add':
                $event = EventModel::create([
                    'nama_event' => $request->title,
                    'tanggal_mulai' => $request->start,
                    'tanggal_selesai' => $request->end,
                ]);

                return response()->json($event);
                break;

            case 'update':
                $event = EventModel::find($request->id);
                if ($event) {
                    $event->update([
                        'nama_event' => $request->title,
                        'tanggal_mulai' => $request->start,
                        'tanggal_selesai' => $request->end,
                    ]);
                    return response()->json($event);
                }
                return response()->json(['error' => 'Event not found'], 404);
                break;

            case 'delete':
                $event = EventModel::find($request->id);
                if ($event) {
                    $event->delete();
                    return response()->json(['message' => 'Event deleted successfully']);
                }
                return response()->json(['error' => 'Event not found'], 404);
                break;

            default:
                return response()->json(['error' => 'Invalid operation'], 400);
        }
    }

    public function calendarData(Request $request)
    {
        $events = EventModel::with('notes')
            ->whereBetween('tanggal_mulai', [$request->start, $request->end])
            ->orWhereBetween('tanggal_selesai', [$request->start, $request->end])
            ->get();

        $data = $events->map(function ($event) {
            return [
                'id' => $event->event_id,
                'title' => $event->nama_event,
                'start' => $event->tanggal_mulai,
                'end' => date('Y-m-d\TH:i:s', strtotime($event->tanggal_selesai . ' +1 day')), // Menambahkan satu hari
            ];
        });

        return response()->json($data);
    }

    public function getEventsForDate(Request $request)
    {
        $date = $request->input('date');

        // Ambil event untuk tanggal tertentu
        $events = EventModel::whereDate('tanggal_mulai', $date)
            ->orWhereDate('tanggal_selesai', $date)
            ->select('event_id', 'nama_event', 'alamat', 'gambar', 'tanggal_mulai', 'tanggal_selesai')
            ->get();

        Log::info('Fetched events for date: ' . $date, ['events' => $events]);

        return response()->json(['events' => $events]);
    }

    public function getDetailEvent(Request $request)
    {
        $event_id = $request->input('id');
        $event = EventModel::where('event_id', $event_id)
            ->select('event_id', 'nama_event', 'alamat', 'gambar', 'tanggal_mulai', 'tanggal_selesai', 'deskripsi')
            ->first();

        if ($event) {
            return response()->json($event);
        }

        return response()->json(['error' => 'Event not found'], 404);
    }

    public function getEvents()
    {
        $events = EventModel::all();
        return response()->json($events);
    }

    public function editEvent($event_id)
    {
        $event = EventModel::findOrFail($event_id);
        return view('kegiatan.edit', compact('event'));
    }
}
