<?php

namespace App\Observers;

use App\LPJ;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class LPJObserver
{
    /**
     * Handle the LPJ "updated" event.
     *
     * @param  \App\LPJ  $LPJ
     * @return void
     */
    public function updated(LPJ $LPJ)
    {
        if ($LPJ->beasiswa_id !== $LPJ->getOriginal('beasiswa_id')) {
            Log::info("Beasiswa in LPJ : {$LPJ->nama} changed");

            // Delete existing permohonan LPJ
            $permohonanLPJ = $LPJ->permohonan()->get();
            foreach ($permohonanLPJ as $row) {
                $row->delete();
                Log::info("Permohonan LPJ {$row->mh_id} deleted !");
            }
        }
        Cache::forget('lpj-' . $LPJ->id);
        Log::info("Cache LPJ : {$LPJ->nama} deleted !");
    }

    /**
     * Handle the LPJ "deleted" event.
     *
     * @param  \App\LPJ  $LPJ
     * @return void
     */
    public function deleted(LPJ $LPJ)
    {
        Cache::forget('lpj-', $LPJ->id);
        Log::info("Cache LPJ : {$LPJ->nama} deleted !");
    }
}
