<?php

namespace App\Observers;

use App\Events\DuplicateFundWarningEvent;
use App\Models\Fund;

class FundObserver
{
    public function created(Fund $fund): void
    {
        $original = Fund::searchByNameOrAlias($fund->name)
            ->whereNot('id', $fund->id)
            ->orderBy('id')
            ->first();

        if ($original) {
            DuplicateFundWarningEvent::dispatch($original, $fund);
        }
    }
}
