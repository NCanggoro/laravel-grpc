<?php

namespace App\Http\Controllers;

use App\Classes\Singleton\Message;
use Illuminate\Http\Request;
use Service\UserServiceClient;
use Grpc\ChannelCredentials;
use Service\UserRequest;

class UserController extends Controller
{
    protected $user_service;
    protected $message;
    public function __construct()
    {
        $this->user_service = new UserServiceClient('0.0.0.0:9001', [
            'credentials' => ChannelCredentials::createInsecure(),
        ]);

        $this->message = Message::getInstance();

        
    }
    //

    public function getUser(Request $request)
    {
        try {
            $user_request = new UserRequest();
            $user_request->setId($request->id);
    
            [$response, $status] = $this->user_service->getUser($user_request)->wait();
    
            if($status->code !== \Grpc\STATUS_OK) {
                return response()->json([
                    'status_code' => $status->code,
                    'message' => $status->details
                ], 422);
            }

            $data = json_decode($response->serializeToJsonString());

            return $this->message->getJsonResponse($status, 'Success', $data->data);
    
            // return response()->json([
            //     'status_code' => $status->code,
            //     'message' => $status->details,
            //     'data' => $data->data
            // ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
