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
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete()->comment('Creador del ticket (User)');
            $table->foreignId('agent_id')->nullable()->constrained('users')->comment('Agente asignado');

            $table->string('ticket_name'); // Ticket name [cite: 18]
            $table->unsignedSmallInteger('ticket_type')->comment('1, 2, 3'); // Ticket type [cite: 19]

            $table->enum('mode_of_transport', ['air', 'land', 'sea']); // Mode of transport [cite: 20]
            $table->string('product'); // Product to import/export [cite: 21]
            $table->string('country_origin'); // Country of origin [cite: 22]
            $table->string('country_destination'); // Country of destination [cite: 22]

            // Statuses: new, in progress, and completed [cite: 23]
            $table->enum('status', ['new', 'in_progress', 'completed'])->default('new');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
