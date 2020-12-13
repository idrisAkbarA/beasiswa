<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\User;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;


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
        $admin->ips         = 3.02;
        $admin->ipk         = 3.02;
        $admin->tgl_lahir   = "1998-12-07";
        $admin->tmpt_lahir  = "Dumai";
        $admin->ukt   = 5;
        $admin->password    = Hash::make("admin123");
        $admin->save();
        echo "\nMhs Account Created\n\nNIM: 11751101939\nPass: 123";

        $faker = Faker::create("id_ID");
        for ($i=0; $i < 10 ; $i++) { 
            $nim = $faker->numerify('117########');
            $admin = new User;
            $admin->nama        = $faker->name;
            $admin->nim         = $nim;
            $admin->jurusan_id  = 1;
            $admin->email       = $nim."@students.uin-suska.ac.id";
            $admin->hp          = "081275553496";
            $admin->semester    = 7;
            $admin->ipk         = 3.02;
            $admin->tgl_lahir   = "1998-12-07";
            $admin->tmpt_lahir  = "Dumai";
            $admin->ukt   = [1,2,3,4,5,6,7][random_int(0,6)];
            $admin->password    = Hash::make("123");
            $admin->save();
            // echo "\nMhs Account Created\n\nNIM: 11751101939\nPass: 123";.
        }
    }
}
