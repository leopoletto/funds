<?php

namespace Database\Factories;

use App\Models\FundAlias;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class FundAliasFactory extends Factory
{
    protected $model = FundAlias::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->company(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
