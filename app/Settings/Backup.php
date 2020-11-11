<?php
namespace App\Settings;
use Illuminate\Support\Facades\Storage;

class Backup{
    public static function list(){
        $files = Storage::files("backup");

        $url = Storage::url(
            $files[0]
        );

        return $url;
        return $files;
    } 
}