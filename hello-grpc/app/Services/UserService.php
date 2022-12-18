<?php

namespace App\Services;

use App\Models\User as DBUser;
use Service\User;
use Service\Error;
use Service\UserRequest;
use Service\UserResponse;
use Service\UserServiceInterface;
use Spiral\GRPC;
use Spiral\GRPC\Exception\InvokeException;
use Spiral\GRPC\StatusCode;

class UserService implements UserServiceInterface
{
    public function getUser(GRPC\ContextInterface $ctx, UserRequest $in): UserResponse
    {
        $userId = $in->getId();
        $response = new UserResponse();
        try {
            $user = DBUser::find($userId);
            if(is_null($user)) {
                throw new InvokeException("User not found.", StatusCode::NOT_FOUND);
            }
            $userProto = new User();
            $userProto->setId($user->id);
            $userProto->setName($user->name);
            $userProto->setEmail($user->email);
            $userProto->setCreatedAt((string) $user->created_at);
            $userProto->setUpdatedAt((string) $user->updated_at);
            $response->setData($userProto);
        }catch(Exception $e) {
            $error = new Error();
            $error->setStatusCode($e->getCode());
            $error->setMessage((string) $e->getMessage());
            $response->setError($error);
        }

        return $response;
    }
}
