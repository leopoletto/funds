<?php

namespace Database\Factories;

use App\Models\Fund;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class FundFactory extends Factory
{
    protected $model = Fund::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->company(),
            'start_year' => $this->faker->year,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
