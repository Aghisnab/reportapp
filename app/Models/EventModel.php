<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventModel extends Model
{
    use HasFactory;
    protected $table = 'events';
    public $timestamps = false;
    protected $fillable = [
        'event_id', 'nama_event', 'tanggal_mulai', 'tanggal_selesai', 'bulan_event', 'alamat', 'deskripsi', 'gambar', 'artikel','note_id',
    ];

    public function notes()
    {
        return $this->hasMany(NotesModel::class, 'event_id', 'event_id'); // Relasi one-to-many dengan NotesModel
    }

    // Define relationship to NotesModel
    public function note()
    {
        return $this->belongsTo(NotesModel::class, 'note_id');
    }

    public function detailevents()
    {
        return $this->hasMany(DetailEventModel::class, 'event_id', 'id');
    }
}

