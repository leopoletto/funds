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

class DuplicateFundRegistrationTest extends TestCase
{
    use RefreshDatabase;

    public Request $request;

    public function __construct(string $name)
    {
        parent::__construct($name);

        $this->request = Request::create('/api/funds');
    }

    public function test_duplicate_fund_registration_when_a_warning_event_is_dispatched()
    {
        $this->actingAs(User::factory()->create());

        $funds = Fund::factory(2)
            ->state(['name' => 'ACME Global Fund'])
            ->for(FundManager::factory())
            ->create();

        $this->assertDatabaseHas('duplicate_funds', [
           'fund_id' => $funds->first()->id,
           'duplicate_fund_id' => $funds->last()->id,
        ]);
    }
}
