<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\UserPetugas;
use Illuminate\Support\Facades\Hash;

class initAdmin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'initAdmin';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Initialize Admin test account';

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
        $admin = new UserPetugas;
        $admin->name = "admin";
        $admin->role = 1;
        // $admin->email= "admin@admin.com";
        $admin->password = Hash::make("admin123");
        $admin->save();
        echo "Admin Account Created\n";

    }
}
