<?php

namespace Database\Factories;

use App\Models\ClickAnalytic;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ClickAnalytic>
 */
class ClickAnalyticFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ClickAnalytic::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'user_id' => null, // Để null
            'ip_address' => $this->faker->ipv4,
            'item_type' => $this->faker->randomElement(['article', 'product']),
            'item_id' => $this->faker->numberBetween(1, 100),
            'page_url' => $this->faker->url,
            'user_agent' => $this->faker->userAgent,
            'clicked_at' => $this->faker->dateTimeBetween('-1 day', 'now'),
        ];
    }
}
