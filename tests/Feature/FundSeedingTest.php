<?php

namespace Tests\Feature;

use App\Http\Resources\FundResource;
use App\Models\Fund;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Tests\TestCase;

class FundSeedingTest extends TestCase
{
    use RefreshDatabase;

    public Request $request;

    public function __construct(string $name)
    {
        parent::__construct($name);

        $this->request = Request::create('/api/funds/seed', 'POST');
    }

    public function test_funds_can_be_seeded()
    {
        $this->actingAs(User::factory()->create());

        $response = $this->post('/api/funds/seed', [
            'amount' => 5,
        ]);

        $response->assertStatus(200)
            ->assertJsonCount(5, 'data')
            ->assertSimilarJson(
                FundResource::collection(Fund::all())
                    ->toResponse($this->request)
                    ->getData(true)
            );
    }

    public function test_funds_can_be_seeded_with_some_duplicate()
    {
        $this->actingAs(User::factory()->create());

        $response = $this->post('/api/funds/seed', [
            'amount' => 5,
            'duplicate' => 4,
        ]);

        $response->assertStatus(200)
            ->assertExactJson(
                FundResource::collection(Fund::all())
                    ->toResponse($this->request)
                    ->getData(true)
            );
    }
}
