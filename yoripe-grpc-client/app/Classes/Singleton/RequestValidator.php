<?php

namespace App\Classes\Singleton;

Use App\Classes\Singleton\Message;

use Validator;

class RequestValidator
{
    private static $instance=null;

    public static function getInstance()
    {
        if(!self::$instance)
        {
            self::$instance = new RequestValidator();
        }

        return self::$instance;
    }

    public static function validate($data,$rules)
    {

        $validated = Validator::make($data,$rules);
        
        if($validated->fails()){
            return response()->json([
                'status_code' => 400,
                'message' => $validated->errors(),
            ],400);
            
        }

    }
}
