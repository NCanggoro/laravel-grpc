<?php

namespace App\Http\Controllers;

use App\Classes\Singleton\Message;
use App\Classes\Singleton\RequestValidator;

use Validator;

use Service\UserServiceClient;
use Google\Rpc\Status;
use Grpc\ChannelCredentials;
use Service\AuthServiceClient;
use Service\UserRegisterRequest;
use Service\UserLoginRequest;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    protected $auth_service;
    protected $message;
    protected $validator;

    public function __construct()
    {
        $this->auth_service = new AuthServiceClient('0.0.0.0:9001', [
            'credentials' => ChannelCredentials::createInsecure(),
        ]);

        $this->message = Message::getInstance();
        $this->validator = RequestValidator::getInstance();
    }

    public function register(Request $request)
    {
        try {
            $this->validator::validate($request->all(), [
                'email' => 'required|email',
                'password' => 'required',
                'name' => 'required'
            ]);
    
            $user_request = new UserRegisterRequest();
            $user_request->setEmail($request->email);
            $user_request->setName($request->name);
            $user_request->setPassword($request->password);
    
            [$response, $status] = $this->auth_service->userRegister($user_request)->wait();

            if($status->code !== \Grpc\STATUS_OK) {
                return $this->message->errorJsonResponse($status);
            }

            $data = json_decode($response->serializeToJsonString());

            return $this->message->getJsonResponse($status, "Success", $data->data);

        } catch(Exception $e) {
            return response()->json([
                'message' => 'error',
                'data' => $e->getMessage()
            ], 500);
        }   
    }

    public function login(Request $request)
    {
        try {
            $this->validator->validate($request->all(), [
                'email' => 'required|email',
                'password' => 'required',
            ]);

            $user_request = new UserLoginRequest();
            $user_request->setEmail($request->email);
            $user_request->setPassword($request->password);
    
            [$response, $status] = $this->auth_service->userLogin($user_request)->wait();

            if($status->code !== \Grpc\STATUS_OK) {
                return $this->message->errorJsonResponse($status);
            }

            $data = json_decode($response->serializeToJsonString());

            return $this->message->getJsonResponse($status, "Success", $data->data);

        } catch(Exception $e) {
            return response()->json([
                'message' => 'error',
                'data' => $e->getMessage()
            ], 500);
        }   
    }
}
