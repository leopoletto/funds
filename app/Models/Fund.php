<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Fund extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'name',
        'start_year',
        'fund_manager_id',
    ];

    public function fundManager(): BelongsTo
    {
        return $this->belongsTo(FundManager::class);
    }

    public function fundAliases(): HasMany
    {
        return $this->hasMany(FundAlias::class);
    }

    public function companies(): BelongsToMany
    {
        return $this->belongsToMany(Company::class);
    }

    public function scopeSearchByNameOrAlias(Builder $query, string $name): Builder
    {
        return $query->where(function (Builder $q) use ($name) {
            $q->where('name', 'LIKE', '%' . $name . '%')
                ->orWhereHas('fundAliases', function (Builder $q) use ($name) {
                    $q->where('name', 'LIKE', '%' . $name . '%');
                });
        });
    }

    public function duplicateFunds(): BelongsToMany
    {
        return $this->belongsToMany(Fund::class, 'duplicate_funds', 'fund_id', 'duplicate_fund_id')
            ->withTimestamps();
    }
}
