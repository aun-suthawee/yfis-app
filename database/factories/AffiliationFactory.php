<?php

namespace Database\Factories;

use App\Models\Affiliation;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Affiliation>
 */
class AffiliationFactory extends Factory
{
    protected $model = Affiliation::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->company(),
        ];
    }
}
