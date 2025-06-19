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
        Schema::create('obwis', function (Blueprint $table) {
            $table->id();
            $table->string('obwis_id', 11);
            $table->string('nama_obwis');
            $table->date('tanggal_buka');
            $table->decimal('tarif', 8, 2); // Tarif dengan dua digit desimal
            $table->string('alamat');
            $table->string('maps')->nullable(); // Field untuk menyimpan link atau koordinat maps
            $table->string('gambar', 255); // Field untuk menyimpan nama file gambar
            $table->timestamps(); // Menambahkan created_at dan updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('obwis');
    }
};
