<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('event_id', 11)->unique();
            $table->string('nama_event', 255);
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai');
            $table->string('bulan_event', 7); // Format YYYY-MM
            $table->text('alamat');
            $table->text('deskripsi');
            $table->string('gambar', 255); // URL gambar event
            $table->timestamps(); // optional, if you want created_at and updated_at
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
