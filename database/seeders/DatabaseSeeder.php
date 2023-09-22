<?php

namespace Database\Seeders;

use App\Actions\SeedFundTableAction;
use Illuminate\Database\Seeder;
class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        app(SeedFundTableAction::class)->run(10, 5);
    }
}
