<?php

namespace App\Http\Controllers;

use App\Http\Action\User\ListUserAction;
use App\Http\Action\User\CreateUserAction;
use App\Http\Requests\UserRequest;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Create a new user.
     *
     * @param UserRequest 
     * @return UserResource
     */
    public function create(UserRequest $request)
    {
        return (new CreateUserAction($request->safe()->all()))->execute();
    }


    function list(Request $request) {
        return (new ListUserAction($request->all()))->execute();
    }
}
