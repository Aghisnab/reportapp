<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNoteIdToEventsTable extends Migration
{
    public function up()
    {
        Schema::table('events', function (Blueprint $table) {
            $table->unsignedBigInteger('note_id')->nullable()->after('gambar'); // Menambahkan kolom note_id setelah gambar
            $table->foreign('note_id')->references('id')->on('notes')->onDelete('cascade'); // Relasi ke tabel notes
        });
    }

    public function down()
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropForeign(['note_id']);
            $table->dropColumn('note_id');
        });
    }
}
