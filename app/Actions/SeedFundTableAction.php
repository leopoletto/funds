<?php

namespace App\Actions;

use App\Models\Company;
use App\Models\Fund;
use App\Models\FundAlias;
use App\Models\FundManager;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\Sequence;

class SeedFundTableAction
{
    public function run(int $amount, int $duplicate = 0): Collection
    {
        $fundManagers = FundManager::factory(5)->create();
        $companies = Company::factory(30)->create();

        $funds = Fund::factory($amount)
            ->sequence(fn(Sequence $sequence) => ['fund_manager_id' => $fundManagers[rand(0, 4)]])
            ->has(FundAlias::factory()->count(rand(1, 5)))
            ->afterCreating(function (Fund $fund) use ($companies) {
                $fund->companies()->attach($companies->random(rand(1, 30))->pluck('id'));
            })
            ->create();

        $duplicates = collect();

        if ($duplicate) {
            $funds->random($duplicate)->each(function (Fund $fund) use ($duplicates) {
                for ($i = 0; $i < rand(1, 5); $i++) {
                    $replica = $fund->replicate();
                    $replica->push();
                    $duplicates->push($replica);
                }
            });
        }

        return $funds->merge($duplicates);
    }
}
