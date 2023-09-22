<?php

namespace Tests\Feature;

use App\Models\Fund;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FundShow extends TestCase
{
    use RefreshDatabase;

    public function test_it_shows_the_fund_data()
    {
        $this->actingAs(User::factory()->create());

        $this->assertDatabaseCount(Fund::class, 0);

        $fund = Fund::factory()
            ->forFundManager()
            ->create();

        $response = $this->get('/api/funds/' . $fund->id);

        $response->assertSuccessful()
            ->assertSee($fund->toArray());

        $this->assertDatabaseCount(Fund::class, 1);
    }
}
