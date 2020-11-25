<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Artisan;
class pull implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $updatedFileList = $this->getGitUpdate(); // get updated filename from git
        // echo "Pulling Data..";
        $this->downloadUpdate(); // pull from git repo
        
        // do file checks and process it accordingly
        // echo "Looping data..";
        foreach ($updatedFileList as $key => $value) {
            $isMigrationFile = substr($value,-9) == "table.php";
            if($value=="package.json") $this->npmInstall();
            if($value=="composer.json") $this->composerInstall();
            if($isMigrationFile) $this->migrate();
        }
        // build the app
        // echo "building..";
        $this->build();       
    }
    public function getGitUpdate(){
        // this function checks what files that have been updated
        $command = "git fetch origin";
        $returnVar = NULL;
        $output  = NULL;
        exec($command, $output, $returnVar);

        $command = "git diff master origin/master --name-only";
        $returnVar = NULL;
        $output  = NULL;
        exec($command, $output, $returnVar);
        return $output;
    }
    public function downloadUpdate(){
        // this function pulls the updated files from git
        $command = "git pull";
        $returnVar = NULL;
        $output  = NULL;
        exec($command, $output, $returnVar);
    }
    public function build(){
        // this functions builds the javascript and scss files
        // by doing npm run prod
        $command = "npm run prod";
        $returnVar = NULL;
        $output  = NULL;
        exec($command, $output, $returnVar);
    }
    public function npmInstall(){
        // this function installs new npm packages
        $command = "npm install";
        $returnVar = NULL;
        $output  = NULL;
        exec($command, $output, $returnVar);
    }
    public function composerInstall(){
        // this function installs new composer packages
        $command = "composer install";
        $returnVar = NULL;
        $output  = NULL;
        exec($command, $output, $returnVar);
    }
    public function migrate(){
        // this function migrates the db
        Artisan::call("migrate");
    }
    public function startQueue(){
        // this functioon starts the process
        // by calling "php artisan queue:work"
        Artisan::call("queue:work");
    }
}
