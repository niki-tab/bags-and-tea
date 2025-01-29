<?php

declare(strict_types=1);

namespace Src\Authorization\Users\Infrastructure;

use Ramsey\Uuid\Uuid;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use MikeMcLin\WpPassword\Facades\WpPassword;
use Src\Authorization\Users\Domain\UserModel;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Src\Authorization\Users\Domain\UserRolesModel;
use Src\Environments\Customers\Domain\CustomerModel;
use Src\Shared\Infrastructure\Controller\ApiController;

class PostGenerateAuthTokenByUserId extends ApiController
{
    public function __invoke($userId): Response
    {   
        
        $user = UserModel::where("id", $userId)->first();
        
        if($user){

            $token = $user->createToken('AdminPanelAdministrator')->plainTextToken;
            return $this->successResponse(Response::HTTP_CREATED, $token);

        }else{

            return $this->errorResponse(Response::HTTP_CONFLICT, 998, "This user doesn't exist.");
        }

        
    }
}