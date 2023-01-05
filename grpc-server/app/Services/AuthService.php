<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User as DBUser;
use Service\User;
use Service\Error;
use Service\UserRegisterRequest;
use Service\UserRegisterResponse;
use Service\UserLoginResponse;
use Service\UserLoginRequest;
use Service\AuthServiceInterface;
use Spiral\GRPC;
use Spiral\GRPC\Exception\InvokeException;
use Spiral\GRPC\StatusCode;


class AuthService implements AuthServiceInterface
{
    public function userRegister(GRPC\ContextInterface $ctx, UserRegisterRequest $in): UserRegisterResponse
    {
        $email = $in->getEmail();
        $name = $in->getName();
        $password = $in->getPassword();
        $response = new UserRegisterResponse();
        $userProto = new User();

        if(DBUser::where('email', '=', $email)->count() > 0) {
            throw new InvokeException("Email Already Exist.", StatusCode::ALREADY_EXISTS);
        }

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

        $response->setData($userProto);

        return $response;
    }

    public function userLogin(GRPC\ContextInterface $ctx, UserLoginRequest $in): UserLoginResponse
    {
        $email = $in->getEmail();
        $password = $in->getPassword();
        $response = new UserLoginResponse();
        $userProto = new User();
        $user = DBUser::where(['email' => $email])->first();

        if(is_null($user)) {
            throw new InvokeException("User not found.", StatusCode::NOT_FOUND);
        }

        if(!Hash::check($password, $user->password)){
            throw new InvokeException("Invalid Password.", StatusCode::UNAUTHENTICATED);
        }

        if($user->tokens()->where('name',$email)->first()) {
            $user->tokens()->where('tokenable_id', $user->id)->delete();
        }

        $token = $user->createToken($email)->plainTextToken;

        $userProto->setId($user->id);
        $userProto->setName($user->name);
        $userProto->setEmail($user->email);
        $userProto->setToken($token);
        $userProto->setCreatedAt((string) $user->created_at);
        $userProto->setUpdatedAt((string) $user->updated_at);

        $response->setData($userProto);

        return $response;

    }
}
