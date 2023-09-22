<?php

namespace Tests\Feature;

use App\Events\DuplicateFundWarningEvent;
use App\Http\Resources\FundResource;
use App\Models\Company;
use App\Models\Fund;
use App\Models\FundManager;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class FundCreationTest extends TestCase
{
    use RefreshDatabase;

    public Request $request;

    public function __construct(string $name)
    {
        parent::__construct($name);

        $this->request = Request::create('/api/funds');
    }

    public function test_fund_can_be_created()
    {
        $this->actingAs(User::factory()->create());

        $companies = Company::factory(2)->create();
        $fundManager = FundManager::factory()->create();

        $this->assertDatabaseCount(Fund::class, 0);

        $response = $this->post('/api/funds', [
            'name' => 'ACME Global Fund',
            'start_year' => '2010',
            'aliases' => ['ACME Fund', 'ACME Global'],
            'companies' => $companies->pluck('id')->toArray(),
            'fund_manager_id' => $fundManager->id
        ]);

        $response->assertCreated()
            ->assertExactJson(
                (new FundResource(Fund::first()))
                    ->response($this->request)
                    ->getData(true)
            );

        $this->assertDatabaseCount(Fund::class, 1);
    }

    public function test_duplicate_fund_creation_dispatch_an_event()
    {
        $this->actingAs(User::factory()->create());

        Event::fake([
            DuplicateFundWarningEvent::class
        ]);

        Fund::factory(2)
            ->state(['name' => 'ACME Global Fund'])
            ->for(FundManager::factory())
            ->hasFundAliases(['name' => 'Alias Fund'])
            ->create();

        Event::assertDispatched(DuplicateFundWarningEvent::class);

        Fund::factory()
            ->state(['name' => 'Alias Fund'])
            ->for(FundManager::factory())
            ->create();

        Event::assertDispatched(DuplicateFundWarningEvent::class);

        $this->assertDatabaseCount(Fund::class, 3);
    }
}
