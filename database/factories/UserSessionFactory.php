<?php

namespace Database\Factories;

use App\Models\UserSession;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\UserSession>
 */
class UserSessionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = UserSession::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $startedAt = $this->faker->dateTimeBetween('-1 day', 'now');
        $duration = $this->faker->numberBetween(60, 3600); // 1 phút đến 1 giờ
        $endedAt = (clone $startedAt)->modify("+{$duration} seconds");

        return [
            'user_id' => null, // Để null để tránh foreign key issue
            'session_id' => Str::random(40),
            'ip_address' => $this->faker->ipv4,
            'user_agent' => $this->faker->userAgent,
            'started_at' => $startedAt,
            'ended_at' => $this->faker->boolean(80) ? $endedAt : null, // 80% có ended_at
            'duration_seconds' => $this->faker->boolean(80) ? $duration : 0,
            'total_clicks' => $this->faker->numberBetween(0, 20),
        ];
    }
}
