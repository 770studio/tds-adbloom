<?php

namespace Database\Factories;

use App\Models\Conversion;
use Illuminate\Database\Eloquent\Factories\Factory;

class ConversionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Conversion::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [

            'Stat_tune_event_id' => $this->faker->uuid(),
            'Stat_payout' => $this->faker->randomFloat(2, 0, 9999),



        ];
    }
}
