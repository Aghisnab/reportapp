<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class NotesModel extends Model
{
    use HasFactory;
    protected $table = 'notes';
    public $timestamps = false;
    protected $fillable = ['tanggal_catatan', 'isi_catatan'];
    public function event()
    {
        return $this->hasOne(EventModel::class, 'note_id');
    }

}
