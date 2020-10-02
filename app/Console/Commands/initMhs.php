<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\User;
use Illuminate\Support\Facades\Hash;

class initMhs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'initMhs';

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
        $admin = new User;
        $admin->nama        = "Idris Akbar Adyusman";
        $admin->nim         = "11751101939";
        $admin->jurusan_id  = 1;
        $admin->email       = "11751101939@students.uin-suska.ac.id";
        $admin->hp          = "081275553496";
        $admin->semester    = 7;
        $admin->ipk         = 3.02;
        $admin->tgl_lahir   = "1998-12-07";
        $admin->tmpt_lahir  = "11751101939@students.uin-suska.ac.id";
        $admin->password    = Hash::make("123");
        $admin->save();
        echo "\nMhs Account Created\n\nNIM: 11751101939\nPass: 123";
    }
}
