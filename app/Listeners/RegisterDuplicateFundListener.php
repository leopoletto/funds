<?php

namespace App\Listeners;

use App\Events\DuplicateFundWarningEvent;

class RegisterDuplicateFundListener
{
    public function __construct()
    {
    }

    public function handle(DuplicateFundWarningEvent $event): void
    {
        $event->original->duplicateFunds()->attach($event->duplicate->id);
    }
}
