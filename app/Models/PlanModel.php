<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlanModel extends Model
{
    use HasFactory;

    protected $table = 'plans';
    public $timestamps = false;
    protected $fillable = [
        'event_id',
        'nama_event',
        'tanggal_mulai',
        'tanggal_selesai',
        'bulan_event',
        'alamat',
        'deskripsi',
        'gambar',
        'event_selesai'
    ];

    // Enum values for bulan_event
    const BULAN_EVENT_ENUM = [
        'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
        'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
    ];

    protected $casts = [
        'bulan_event' => 'string',
        'event_selesai' => 'boolean',
    ];
}
