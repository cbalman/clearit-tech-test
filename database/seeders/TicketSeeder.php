<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Ticket;
use App\Models\User; // Necesario para obtener IDs de usuario

class TicketSeeder extends Seeder
{
    public function run()
    {
        // 1. Obtener los IDs de un agente y un usuario para las asignaciones fijas.
        // Asumimos que los seeders de usuarios ya se ejecutaron.
        $user = User::where('role', 'user')->first();
        $agent = User::where('role', 'agent')->first();

        // --- TICKETS FIJOS (Ejemplos de la vida real) ---

        // Ticket 1: Abierto y asignado a un agente
        Ticket::factory()->create([
            'user_id' => $user->id,
            'agent_id' => $agent->id,
            'ticket_name' => 'API returning 500 error',
            'ticket_type' => 1,
            'mode_of_transport' => 'sea',
            'product' => 'Electronics',
            'country_origin' => 'CN',
            'country_destination' => 'US',
            'status' => 'in_progress'
        ]);

        // Ticket 2: Abierto, sin asignar
        Ticket::factory()->create([
            'user_id' => $user->id,
            'agent_id' => null,
            'ticket_name' => 'Database migration failing',
            'ticket_type' => 2,
            'mode_of_transport' => 'air',
            'product' => 'Textiles',
            'country_origin' => 'MX',
            'country_destination' => 'CA',
            'status' => 'new'
        ]);

        // Ticket 3: Cerrado y completado
        Ticket::factory()->create([
            'user_id' => $user->id,
            'agent_id' => $agent->id,
            'ticket_name' => 'Frontend UI broken in mobile',
            'ticket_type' => 3,
            'mode_of_transport' => 'land',
            'product' => 'Machinery',
            'country_origin' => 'BR',
            'country_destination' => 'AR',
            'status' => 'completed'
        ]);

        // --- TICKETS ALEATORIOS ---
        // Generar 10 tickets más usando los datos aleatorios del Factory
        Ticket::factory(10)->create();
    }
}
