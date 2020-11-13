<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class pull extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pull';

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
        echo "pulling from repo..\n";
        $command = "git pull";
        
        $returnVar = NULL;
        $output  = NULL;
        
        exec($command, $output, $returnVar);

        echo "\npulled\n";
        echo "\nrunning npm to build..\n";
        $command = "npm run prod";
        
        $returnVar = NULL;
        $output  = NULL;
        
        echo exec($command, $output, $returnVar);
        echo "\nDone";
    }
}
