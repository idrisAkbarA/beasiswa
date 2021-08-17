<?php

namespace App\Observers;

use App\PermohonanLPJ;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class PermohonanLPJObserver
{
    /**
     * Handle the permohonan l p j "created" event.
     *
     * @param  \App\PermohonanLPJ  $permohonanLPJ
     * @return void
     */
    public function created(PermohonanLPJ $permohonanLPJ)
    {
        Cache::forget('lpj-', $permohonanLPJ->lpj_id);
        Log::info("cache key deleted");
    }

    /**
     * Handle the permohonan l p j "updated" event.
     *
     * @param  \App\PermohonanLPJ  $permohonanLPJ
     * @return void
     */
    public function updated(PermohonanLPJ $permohonanLPJ)
    {
        if (Cache::has('lpj-' . $permohonanLPJ->getOriginal('lpj_id'))) {
            Log::info("(Updated) cache key deleted, deleted key: lpj-" . $permohonanLPJ->getOriginal('lpj_id'));
        }
        // Cache::forget('lpj-' . $permohonanLPJ->getOriginal('lpj_id'));
    }

    public function updating(PermohonanLPJ $permohonanLPJ)
    {
        //
    }

    public function saving(PermohonanLPJ $permohonanLPJ)
    {
        Cache::forget('lpj-' . $permohonanLPJ->getOriginal('lpj_id'));
        Cache::forget('lpj-' . $permohonanLPJ->lpj_id);
        Log::info("(saving) Deleting cache key, key: lpj-" . $permohonanLPJ->getOriginal('lpj_id') . ", lpj-" . $permohonanLPJ->lpj_id);
    }

    /**
     * Handle the permohonan l p j "deleted" event.
     *
     * @param  \App\PermohonanLPJ  $permohonanLPJ
     * @return void
     */
    public function deleted(PermohonanLPJ $permohonanLPJ)
    {
        Cache::forget('lpj-', $permohonanLPJ->lpj_id);
        Log::info("cache key deleted");
    }

    /**
     * Handle the permohonan l p j "restored" event.
     *
     * @param  \App\PermohonanLPJ  $permohonanLPJ
     * @return void
     */
    public function restored(PermohonanLPJ $permohonanLPJ)
    {
        //
    }

    /**
     * Handle the permohonan l p j "force deleted" event.
     *
     * @param  \App\PermohonanLPJ  $permohonanLPJ
     * @return void
     */
    public function forceDeleted(PermohonanLPJ $permohonanLPJ)
    {
        //
    }
}
