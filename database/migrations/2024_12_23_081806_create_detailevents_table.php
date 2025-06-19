<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDetaileventsTable extends Migration
{
    public function up()
    {
        Schema::create('detailevents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained('events')->onDelete('cascade'); // Relasi ke tabel events
            $table->integer('hari_ke'); // Hari ke-
            $table->date('tanggal'); // Tanggal
            $table->string('rangkaian_acara'); // Rangkaian acara
            $table->string('dokumentasi')->nullable(); // Dokumentasi/Gambar
            $table->timestamps(); // Timestamps untuk created_at dan updated_at
        });
    }

    public function down()
    {
        Schema::dropIfExists('detailevents');
    }
}
