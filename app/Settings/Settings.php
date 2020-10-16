<?php
namespace App\Settings;
class Settings 
{
    public static function set(array $data){
        $file = file_get_contents(app_path('Settings/app.json'));
        $ArrSettings = json_decode($file,true);
        foreach ($data as $key => $value) {
            $ArrSettings[$key] = $value;
        }
        $updatedSettings = fopen("newfile.txt", "w");
    }
    public static function get(){
        $file = file_get_contents(app_path('Settings/app.json'));
        // return $file;
        return json_decode($file,true);
        return app_path('Settings/');
    }
}
