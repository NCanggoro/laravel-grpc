<?php

namespace App\Classes\Singleton;

class Message
{
    private static $instance=null;

    public static function getInstance()
    {
        if(!self::$instance)
        {
            self::$instance = new Message();
        }

        return self::$instance;
    }

    public static function getJsonResponse($status,$message,$data)
    {
        return response()->json([
            'status_code'=>$status->code,
            'message'=>$message,
            'data'=> $data
        ], 200);

    }

    public function errorJsonResponse($status)
    {
        $status_code = $this->mapGrpcToHtppCode($status->code);
        
        return response()->json([
            'status_code' => $status->code,
            'message' => $status->details,
        ], $status_code);
    }

    protected function mapGrpcToHtppCode($code)
    {
        $codes = [200, 499, 520, 422, 504, 404, 409, 403, 429, 400, 499, 400, 501, 500, 503, 408, 401];

        return $codes[$code];
    }

}