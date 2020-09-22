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
        $admin->name = "11751101939";
        $admin->email = "11751101939@students.uin-suska.ac.id";
        $admin->password = Hash::make("123");
        $admin->save();
        echo "\nMhs Account Created\n\nNIM: 11751101939\nPass: 123";
    }
}
