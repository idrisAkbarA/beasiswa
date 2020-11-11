<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;
class backupDB extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'backupDB';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'backup by generating .sql file';

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
        echo "Generating backup file..\n";
        $filename = "backup-" . Carbon::now()->format('Y-m-d-H-i-s') . ".sql";
        
        $command = "mysqldump --user=" . env('DB_USERNAME') ." --password=" . env('DB_PASSWORD') . " --host=" . env('DB_HOST') . " " . env('DB_DATABASE') . " > " . storage_path() . "/app/backup/" . $filename;
        
        $returnVar = NULL;
        $output  = NULL;
        
        exec($command, $output, $returnVar);
        echo "Backup file path: ".storage_path() . "/app/backup/".$filename;
    }
}
