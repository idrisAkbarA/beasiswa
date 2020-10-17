<?php
namespace App\Settings;
class Settings 
{
    public static function set(array $data){
        $file = file_get_contents(app_path('Settings/app.json'));
        $arrSettings = json_decode($file,true);
        foreach ($data as $key => $value) {
            $arrSettings[$key] = $value;
        }
        $updatedSettings = fopen(app_path('Settings/app.json'), "w");
        fwrite($updatedSettings, json_encode($arrSettings));
        fclose($updatedSettings);
        // return $data;
        // return $arrSettings;
        return true;
    }
    public static function get(){
        $file = file_get_contents(app_path('Settings/app.json'));
        // return $file;
        return json_decode($file,true);
        return app_path('Settings/');
    }
}
