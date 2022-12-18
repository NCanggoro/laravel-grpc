<?php

namespace App\Http\Controllers;

use Validator;

use Service\UserServiceClient;
use Grpc\ChannelCredentials;
use Service\UserRequest;
use Service\UserSignUpRequest;
use Service\UserSignInRequest;

use Illuminate\Http\Request;

class AuthController extends Controller
{
    protected $grpc_client;
    public function __construct()
    {
        $this->grpc_client = new UserServiceClient('0.0.0.0:9001', [
            'credentials' => ChannelCredentials::createInsecure(),
        ]);
    }

    public function getUser(Request $request)
    {
        $user_request = new UserRequest();
        $user_request->setId($request->id);

        list($response, $status) = $this->grpc_client->getUser($user_request)->wait();

        return response()->json(json_decode($response->serializeToJsonString()), 200);
    }

    public function register(Request $request)
    {
        try {
            $userValidator = Validator::make($request->all(), [
                'email' => 'required',
                'password' => 'required',
                'name' => 'required'
            ]);
    
            if($userValidator->fails()) {
                return response()->json(['message' => $userValidator->errors()], 400);
            }
    
            $user_request = new UserSignUpRequest();
            $user_request->setEmail($request->email);
            $user_request->setName($request->name);
            $user_request->setPassword($request->password);
    
            list($response, $status) = $this->grpc_client->userSignUp($user_request)->wait();
    
            return response()->json([
                'message' => 'Success',
                'data' => json_decode($response->serializeToJsonString())
            ], 200);

        } catch(\Exception $e) {
            return response()->json([
                'message' => 'error',
                'data' => $e->getMessage()
            ]);
        }   
    }

    public function login(Request $request)
    {
        try {
            $userValidator = Validator::make($request->all(), [
                'email' => 'required',
                'password' => 'required',
            ]);
    
            if($userValidator->fails()) {
                return response()->json(['message' => $userValidator->errors()], 400);
            }
    
            $user_request = new UserSignInRequest();
            $user_request->setEmail($request->email);
            $user_request->setPassword($request->password);
    
            list($response, $status) = $this->grpc_client->userSignIn($user_request)->wait();
    
            return response()->json(json_decode($response->serializeToJsonString()), 200);

        } catch(\Exception $e) {
            return response()->json([
                'message' => 'error',
                'data' => $e->getMessage()
            ]);
        }   
    }
}
