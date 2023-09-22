<?php

namespace Tests\Feature;

use App\Models\Fund;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FundDeletionTest extends TestCase
{
    use RefreshDatabase;

    public function test_fun_can_be_deleted()
    {
        $this->actingAs(User::factory()->create());

        $fund = Fund::factory()
            ->forFundManager()
            ->create();

        $this->assertDatabaseHas(Fund::class, ['id' => $fund->id, 'deleted_at' => null]);

        $response = $this->delete('/api/funds/' . $fund->id);
        $response->assertSuccessful();

        $this->assertDatabaseMissing(Fund::class, ['id' => $fund->id, 'deleted_at' => null]);
    }
}
