<?php

namespace App\Console\Commands;

use App\Jurusan;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class initJurusan extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'initJurusan';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $admin = new Jurusan;
        $admin->fakultas_id      = 1;
        $admin->nama             = "Teknik Informatika";
        $admin->singkatan        = "TIF";
        $admin->save();
        echo "Jurusan Created\n";
    }
}
