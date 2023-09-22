<?php

namespace Tests\Feature;

use App\Http\Resources\FundResource;
use App\Models\Fund;
use App\Models\FundManager;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FundSearchTest extends TestCase
{
    use RefreshDatabase;

    public Request $request;

    public function __construct(string $name)
    {
        parent::__construct($name);

        $this->request = Request::create('/api/funds');
    }

    public function test_funds_can_be_listed()
    {
        $this->actingAs(User::factory()->create());

        Fund::factory(10)
            ->forFundManager()
            ->hasCompanies(5)
            ->hasFundAliases(2)
            ->hasAttached(Fund::factory()->forFundManager(), [], 'duplicateFunds')
            ->create();

        $response = $this->get('/api/funds');

        $response->assertStatus(200)
            ->assertJsonCount(20, 'data')
            ->assertJsonFragment(
                FundResource::collection(Fund::all())
                    ->response($this->request)
                    ->getData(true)
            );
    }

    public function test_funds_can_be_searched_by_name()
    {
        $this->actingAs(User::factory()->create());

        Fund::factory()
            ->state(['name' => 'ACME Global Fund'])
            ->forFundManager()
            ->hasFundAliases()
            ->create();

        Fund::factory()
            ->state(['name' => 'Another Company'])
            ->forFundManager()
            ->hasFundAliases(['name' => 'ACME America Fund'])
            ->create();

        Fund::factory(10)
            ->forFundManager()
            ->hasFundAliases(2)
            ->create();

        $response = $this->get('/api/funds?name=ACME');

        $response->assertStatus(200)
            ->assertJsonCount(2, 'data')
            ->assertJsonFragment(
                FundResource::collection(Fund::searchByNameOrAlias('ACME')->get())
                    ->response($this->request)
                    ->getData(true)
            );
    }

    public function test_funds_can_be_searched_by_start_year()
    {
        $this->actingAs(User::factory()->create());

        Fund::factory(2)
            ->state(['start_year' => 2023])
            ->forFundManager()
            ->create();

        Fund::factory(10)
            ->state(['start_year' => 2000])
            ->forFundManager()
            ->create();

        $response = $this->get('/api/funds?start_year=2023');

        $response->assertStatus(200)
            ->assertJsonCount(2, 'data')
            ->assertJsonFragment(
                FundResource::collection(Fund::where('start_year', 2023)->get())
                    ->response($this->request)
                    ->getData(true)
            );
    }

    public function test_funds_can_be_searched_by_fund_manager_id()
    {
        $this->actingAs(User::factory()->create());

        $fundManager = FundManager::factory()->create();

        Fund::factory(2)
            ->for($fundManager)
            ->create();

        Fund::factory(10)
            ->forFundManager()
            ->create();

        $response = $this->get('/api/funds?fund_manager_id=' . $fundManager->id);

        $response->assertStatus(200)
            ->assertJsonCount(2, 'data')
            ->assertJsonFragment(
                FundResource::collection(Fund::where('fund_manager_id', $fundManager->id)->get())
                    ->response($this->request)
                    ->getData(true)
            );
    }
}
