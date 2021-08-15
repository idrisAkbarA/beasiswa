<?php

namespace App\Observers;

use App\Beasiswa;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class BeasiswaObserver
{
    /**
     * Handle the Beasiswa "updated" event.
     *
     * @param  \App\Beasiswa  $beasiswa
     * @return void
     */
    public function updated(Beasiswa $beasiswa)
    {
        $LPJs = $beasiswa->lpj()->get();
        foreach ($LPJs as $row) {
            Cache::forget('lpj-', $row->id);
            Log::info("Cache LPJ : {$row->nama} deleted !");
        }
    }

    /**
     * Handle the Beasiswa "deleted" event.
     *
     * @param  \App\Beasiswa  $beasiswa
     * @return void
     */
    public function deleted(Beasiswa $beasiswa)
    {
        $LPJs = $beasiswa->lpj()->get();
        foreach ($LPJs as $row) {
            Cache::forget('lpj-', $row->id);
            Log::info("Cache LPJ : {$row->nama} deleted !");
        }
    }
}
