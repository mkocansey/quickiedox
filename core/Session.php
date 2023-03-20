<?php
namespace QuickieDox;

class Session
{

    /**
     * @param array $array
     * @return void
     */
    public static function put(array $array = [])
    {
        if (count($array) > 0 ) {
            foreach ( $array as $key=>$value ) {
                $_SESSION[$key] = $value;
            }
        }
    }

    /**
     * @param array $array
     * @return void
     */
    public static function forget(array $array = [])
    {
        if (count($array) > 0 ) {
            foreach ( $array as $key ) {
                unset($_SESSION[$key]);
            }
        }
    }

}