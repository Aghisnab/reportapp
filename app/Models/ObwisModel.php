<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ObwisModel extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'obwis';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'obwis_id',
        'nama_obwis',
        'cp', // Ganti tanggal_buka menjadi cp
        'alamat',
        'maps',
        'gambar',
        'status',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'cp' => 'string', // Anda bisa menyesuaikan tipe data ini sesuai kebutuhan
        'tarif' => 'decimal:2',
    ];
}
