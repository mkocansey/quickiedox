<?php

namespace App\Core;

use App\Core\App;

class Utilities
{

    static function apiResponse (...$params)
    {
        $params = (object) $params[0];
        $return = [ "status" => $params->status ];
        if ( isset($params->code) ) $return["code"] = $params->code;
        if ( isset($params->message) ) $return["message"] = $params->message;
        if ( isset($params->data) ) $return["data"] = $params->data;
        if(! $params->status ) {
            http_response_code(isset($params->http_response_code) ? $params->http_response_code : 422); //422
        }
        return Utilities::jsonize($return);
    }

    static function getInput()
    {
        return json_decode(file_get_contents('php://input'));
    }

    static function jsonize ($data) {
        header('Content-Type: application/json; charset=utf-8');
        return json_encode($data);
    }


    static function getRandomNumber()
    {
        return rand(1000, getrandmax());
    }

	
	static function truncateString($strString, $maxLength)
    {
		return (strlen($strString) > $maxLength) ? substr($strString, 0, $maxLength).'...' : $strString; 
	}


}