<?php

namespace App\Events;

use App\Models\Fund;
use Illuminate\Foundation\Events\Dispatchable;

class DuplicateFundWarningEvent
{
    use Dispatchable;

    public function __construct(
        public Fund $original, public Fund $duplicate
    ) {}
}
