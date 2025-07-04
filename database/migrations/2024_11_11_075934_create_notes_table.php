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
        Schema::create('notes', function (Blueprint $table) {
            $table->id(); // Primary key, auto-incrementing
            $table->string('event_id', 11); // Foreign key referencing event_reports
            $table->date('tanggal_catatan');
            $table->text('isi_catatan');
            $table->timestamps(); // created_at and updated_at timestamps

            // Defining the foreign key constraint
            $table->foreign('event_id')->references('event_id')->on('events')->onDelete('cascade');
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notes');
    }
};
