<?php

namespace App\Http\Controllers;

use App\Http\Requests\SearchFundsRequest;
use App\Http\Requests\StoreFundRequest;
use App\Http\Requests\UpdateFundRequest;
use App\Http\Resources\FundResource;
use App\Models\Fund;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;

class FundController extends Controller
{
    public function index(SearchFundsRequest $request)
    {
        $searchQuery = Fund::query();

        if ($name = $request->validated('name')) {
            $searchQuery->searchByNameOrAlias($name);
        }

        if ($startYear = $request->validated('start_year')) {
            $searchQuery->where('start_year', $startYear);
        }

        if ($fundManagerId = $request->validated('fund_manager_id')) {
            $searchQuery->where('fund_manager_id', $fundManagerId);
        }

        return FundResource::collection(
            $searchQuery->cursorPaginate(20)
        );
    }

    public function store(StoreFundRequest $request): JsonResource
    {
        $fund = Fund::create([
            'name' => $request->validated('name'),
            'start_year' => $request->validated('start_year'),
            'fund_manager_id' => $request->validated('fund_manager_id')
        ]);

        $fund->companies()->sync(
            $request->validated('companies')
        );

        $fund->fundAliases()->createMany(
            $request->collect('aliases')->map(fn($alias) => ['name' => $alias])
        );

        return new FundResource($fund);
    }

    public function show(Fund $fund): JsonResource
    {
        return new FundResource($fund);
    }

    public function update(UpdateFundRequest $request, Fund $fund): JsonResource
    {
        $fund->fill([
            'name' => $request->validated('name'),
            'start_year' => $request->validated('start_year'),
            'fund_manager_id' => $request->validated('fund_manager_id')
        ])->save();

        $fund->companies()->sync(
            $request->validated('companies')
        );

        $request->collect('aliases')->each(
            fn($alias) => $fund->fundAliases()->updateOrCreate(['name' => $alias])
        );

        return new FundResource($fund);
    }

    public function destroy(Fund $fund): JsonResponse
    {
        $fund->delete();

        return response()->json();
    }
}
