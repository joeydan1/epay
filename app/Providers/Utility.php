<?php
namespace App\Providers;

class Utility 
{
    public static function toJson ($str){

        $jobj = json_decode(stripslashes($str));
        //print( $rawData->name . $rawData->value . "\n");
        foreach ($jobj as $key =>$value){
            print ($key . '->' . $value . "\n");
        }
    }



}



?>