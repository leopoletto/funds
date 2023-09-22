<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReconcileFundRequest;
use App\Models\Fund;
use App\Models\FundAlias;

class ReconcileFundController extends Controller
{
    public function __invoke(ReconcileFundRequest $request, Fund $fund)
    {
        $funds = Fund::whereIn('id', $request->validated('funds'))
            ->with(['fundAliases', 'companies', 'fundManager'])
            ->get();

        $funds->each(function(Fund $currentFund) use ($fund){

            $fund->companies()->syncWithoutDetaching($currentFund->companies->pluck('id'));
            $currentFund->companies()->sync([]);

            $currentFund->fundAliases->each(
                fn(FundAlias $alias) => $alias->fund()->associate($fund)->save()
            );

            $fund->duplicateFunds()
                ->where('fund_id', $fund->id)
                ->where('duplicate_fund_id', $currentFund->id)
                ->detach();

            $currentFund->delete();
        });
    }
}
