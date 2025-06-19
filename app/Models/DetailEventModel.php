<?php namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailEventModel extends Model
{
    use HasFactory;

    protected $table = 'detailevents';

    protected $fillable = [
        'event_id',
        'hari_ke',
        'tanggal',
        'rangkaian_acara',
        'dokumentasi1',
        'dokumentasi2',
    ];

    public function event()
    {
        return $this->belongsTo(EventModel::class, 'event_id', 'id');
    }
}
