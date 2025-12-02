<?php

namespace Database\Factories;

use App\Models\Ticket;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class TicketFactory extends Factory
{
    protected $model = Ticket::class;

    public function definition(): array
    {
        // Obtener IDs de usuarios reales para las relaciones
        $userIds = User::where('role', 'user')->pluck('id')->toArray();
        $agentIds = User::where('role', 'agent')->pluck('id')->toArray();

        // Asegurarse de que haya al menos un agente disponible
        $agentId = empty($agentIds) ? null : $this->faker->randomElement($agentIds);

        return [
            // El user_id debe ser un usuario con role 'user'
            'user_id' => $this->faker->randomElement($userIds),

            // El agent_id puede ser nulo o un agente real
            'agent_id' => $agentId,

            'ticket_name' => $this->faker->sentence(3),
                'ticket_type' => $this->faker->numberBetween(1, 3), // 1, 2, 3 [cite: 19]
                'mode_of_transport' => $this->faker->randomElement(['air', 'land', 'sea']), // [cite: 20]
                'product' => $this->faker->word(), // Producto [cite: 21]
                'country_origin' => $this->faker->countryCode(), // País de origen [cite: 22]
                'country_destination' => $this->faker->countryCode(), // País de destino [cite: 22]
                'status' => $this->faker->randomElement(['new', 'in_progress', 'completed']), // Estados [cite: 23]
        ];
    }
}
