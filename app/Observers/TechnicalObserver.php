<?php

namespace App\Observers;

use App\Models\Technical;

class TechnicalObserver
{
    /**
     * Handle the Technical "created" event.
     */
    public function created(Technical $technical): void
    {
        //
    }

    /**
     * Handle the Technical "updated" event.
     */
    public function updated(Technical $technical): void
    {
        //
    }

    /**
     * Handle the Technical "deleted" event.
     */
    public function deleted(Technical $technical): void
    {
        //
    }

    /**
     * Handle the Technical "restored" event.
     */
    public function restored(Technical $technical): void
    {
        //
    }

    /**
     * Handle the Technical "force deleted" event.
     */
    public function forceDeleted(Technical $technical): void
    {
        //
    }
}
