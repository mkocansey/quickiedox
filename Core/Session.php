<?php
namespace App\Core;


class Session
{

    public static function put($array = [])
    {
        if ( count($array) > 0 ) {
            foreach ( $array as $key=>$value ) {
                $_SESSION[$key] = $value;
            }
        }
    }

    public static function forget($array = [])
    {
        if ( count($array) > 0 ) {
            foreach ( $array as $key ) {
                unset($_SESSION[$key]);
            }
        }
    }

}