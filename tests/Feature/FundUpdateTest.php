<?php

namespace Tests\Feature;

use App\Http\Resources\FundResource;
use App\Models\Company;
use App\Models\Fund;
use App\Models\FundManager;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Tests\TestCase;

class FundUpdateTest extends TestCase
{
    use RefreshDatabase;

    public Request $request;

    public function __construct(string $name)
    {
        parent::__construct($name);

        $this->request = Request::create('/api/funds');
    }

    public function test_fund_can_be_updated()
    {
        $this->actingAs(User::factory()->create());

        $companies = Company::factory(2)->create();
        $fundManager = FundManager::factory()->create();

        $fund = Fund::factory()
            ->state(['name' => 'ABC Global Fund', 'start_year' => '2010'])
            ->forFundManager()
            ->hasCompanies(5)
            ->hasFundAliases(2)
            ->create();

        $response = $this->put('/api/funds/' . $fund->id, [
            'name' => 'ABC Local Fund',
            'start_year' => '2011',
            'aliases' => ['ABC Fund', 'ABC Global'],
            'companies' => $companies->pluck('id')->toArray(),
            'fund_manager_id' => $fundManager->id
        ]);

        $response->assertSuccessful()
            ->assertExactJson(
                (new FundResource($fund->refresh()))
                    ->response($this->request)
                    ->getData(true)
            );
    }
}
