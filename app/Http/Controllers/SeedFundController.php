<?php

namespace App\Http\Controllers;

use App\Actions\SeedFundTableAction;
use App\Http\Requests\SeedFundRequest;
use App\Http\Resources\FundResource;

class SeedFundController extends Controller
{
    public function __invoke(SeedFundRequest $request)
    {
        $funds = app(SeedFundTableAction::class)->run(
            $request->validated('amount'),
            $request->validated('duplicate', 0),
        );

        return FundResource::collection($funds);
    }
}
