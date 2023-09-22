<?php

namespace Tests\Feature;

use App\Models\Fund;
use App\Models\FundManager;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Tests\TestCase;

class FundReconciliationTest extends TestCase
{
    use RefreshDatabase;

    public Request $request;

    public function __construct(string $name)
    {
        parent::__construct($name);

        $this->request = Request::create('/api/funds');
    }


    public function test_duplicate_funds_can_be_reconciled()
    {
        $this->actingAs(User::factory()->create());

        $fundManager = FundManager::factory()->create();

        $funds = Fund::factory(3)
            ->state(['name' => 'ACME Global Fund'])
            ->for($fundManager)
            ->hasFundAliases(2)
            ->hasCompanies(2)
            ->create();

        $this->assertEquals(3, Fund::withoutTrashed()->count());

        /** @var Fund $originalFund */
        $originalFund = $funds->shift();
        $originalFund->loadCount('fundAliases', 'companies');

        $this->assertEquals(2, $originalFund->fund_aliases_count);
        $this->assertEquals(2, $originalFund->companies_count);

        $response = $this->post('/api/funds/' . $originalFund->id . '/reconcile', [
            'funds' => $funds->pluck('id')->toArray()
        ]);

        $fund = $originalFund->loadCount('fundAliases', 'companies');

        $this->assertEquals(6, $fund->fund_aliases_count);
        $this->assertEquals(6, $fund->companies_count);
        $this->assertEquals(1, Fund::withoutTrashed()->count());

        $response->assertStatus(200);
    }
}
