<?php

namespace App\Console\Commands;

use App\Fakultas;
use App\UserPetugas;
use Illuminate\Console\Command;
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
        $admin->nama_lengkap = "Administrator";
        $admin->role = 1;
        $admin->password = Hash::make("admin123");
        $admin->save();

        UserPetugas::create([
            'name' => 'interview',
            'nama_lengkap' => 'Budi Doremi',
            'role' => 2,
            'password' => Hash::make('123')
        ]);

        UserPetugas::create([
            'name' => 'survey',
            'nama_lengkap' => 'Anto Budiantono',
            'role' => 3,
            'password' => Hash::make('123')
        ]);

        UserPetugas::create([
            'name' => 'rektor',
            'nama_lengkap' => 'Mujahid Ahmad',
            'role' => 4,
            'password' => Hash::make('123')
        ]);

        $fakultas = Fakultas::all()->count();
        for ($i=1; $i <= $fakultas; $i++) {
            UserPetugas::create([
                'name' => 'fakultas'.$i,
                'nama_lengkap' => 'Admin Fakultas '.$i,
                'role' => 5,
                'password' => Hash::make('123'),
                'fakultas_id' => $i
            ]);
        }
        echo "Admin Account Created\n";

    }
}
