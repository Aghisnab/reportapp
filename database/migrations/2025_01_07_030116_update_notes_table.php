<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateNotesTableNullableEventId extends Migration
{
    public function up()
    {
        Schema::table('notes', function (Blueprint $table) {
            // Drop the existing foreign key constraint if it exists
            $table->dropForeign(['event_id']);

            // Ensure the event_id column exists and can be null
            $table->unsignedBigInteger('event_id')->nullable()->change();

            // Add the foreign key constraint with ON DELETE SET NULL
            $table->foreign('event_id')
                  ->references('id')
                  ->on('events')
                  ->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('notes', function (Blueprint $table) {
            // Drop the foreign key constraint with ON DELETE SET NULL
            $table->dropForeign(['event_id']);

            // Revert the event_id column to its previous state if necessary
            // Adjust the type if needed and set it to not null
            $table->unsignedBigInteger('event_id')->nullable(false)->change();

            // Add the original foreign key constraint back
            $table->foreign('event_id')
                  ->references('id')
                  ->on('events')
                  ->onDelete('restrict');
        });
    }
}
