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

    public function validate($data,$rules)
    {

        

    }
}
