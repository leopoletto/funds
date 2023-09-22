<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\Fund */
class FundResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $this->load(['fundAliases', 'companies', 'fundManager', 'duplicateFunds']);

        return [
            'id' => $this->id,
            'name' => $this->name,
            'start_year' => $this->start_year,
            'aliases' => FundAliasResource::collection($this->fundAliases),
            'companies' => CompanyResource::collection($this->companies),
            'fund_manager' => new FundManagerResource($this->fundManager),
            'duplicate_funds' => FundResource::collection($this->duplicateFunds),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
