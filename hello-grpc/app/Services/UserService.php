<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User as DBUser;
use Service\User;
use Service\Error;
use Service\UserRequest;
use Service\UserResponse;
use Service\UserSignUpRequest;
use Service\UserSignUpResponse;
use Service\UserSignInResponse;
use Service\SignInResponse;
use Service\UserSignInRequest;
use Service\UserServiceInterface;
use Spiral\GRPC;

class UserService implements UserServiceInterface
{
    public function getUser(GRPC\ContextInterface $ctx, UserRequest $in): UserResponse
    {
        $userId = $in->getId();
        $response = new UserResponse();
        try {
            $user = DBUser::findOrFail($userId);
            $userProto = new User();
            $userProto->setId($user->id);
            $userProto->setName($user->name);
            $userProto->setEmail($user->email);
            $userProto->setCreatedAt((string) $user->created_at);
            $userProto->setUpdatedAt((string) $user->updated_at);
            $response->setUser($userProto);
        } catch(\Exception $e) {
            $error = new Error();
            $error->setCode(1);
            $error->setMessage((string) $e->getMessage());
            $response->setError($error);
        }

        return $response;
    }

    public function userSignUp(GRPC\ContextInterface $ctx, UserSignUpRequest $in): UserSignUpResponse
    {
        $email = $in->getEmail();
        $name = $in->getName();
        $password = $in->getPassword();
        $response = new UserSignUpResponse();
        $userProto = new User();
        try {
            $user = new DBUser();
            $user->name = $name;
            $user->email = $email;
            $user->password = Hash::make($password);
            $user->save();
            $userProto->setId($user->id);
            $userProto->setName($user->name);
            $userProto->setEmail($user->email);
            $userProto->setCreatedAt((string) $user->created_at);
            $userProto->setUpdatedAt((string) $user->updated_at);
            $response->setUser($userProto);
        } catch(\Exception $e) {
            $error = new Error();
            $error->setCode(1);
            $error->setMessage((string) $e->getMessage());
            $response->setError($error);
        }

        return $response;
    }

    public function userSignIn(GRPC\ContextInterface $ctx, UserSignInRequest $in): UserSignInResponse
    {
        $email = $in->getEmail();
        $password = $in->getPassword();
        $response = new UserSignInResponse();
        $signin = new SignInResponse();
        try {
            $user = DBUser::where(['email' => $email])->first();

            if(!Hash::check($password, $user->password)){
                $error = new Error();
                $error->setCode(1);
                $error->setMessage("wrong password");
                $response->setError($error);
            }
            $token = $user->createToken($email)->plainTextToken;

            $signin->setStatus(200);
            $signin->setToken($token);
            $response->setSigninresponse($signin);
        }catch(\Exception $e) {
            $error = new Error();
            $error->setCode(1);
            $error->setMessage((string) $e->getMessage());
            $response->setError($error);
        }

        return $response;

    }
}
