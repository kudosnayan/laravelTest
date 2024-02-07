<?php

namespace App\Http\Action\User;

use App\Exceptions\SuccessResponseException;
use App\Http\Action\User\Base\UserAction;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

class CreateUserAction extends UserAction
{

    /**
     * Handle user registration and return the created user model.
     *
     * @return Model The created user model.
     */
    public function handle(): Model
    {
        $this->model = $this->modelClass::create($this->data)
            ->assignRole($this->data['roles']);
            
        // $user = $this->modelClass::find(1);
        // if (!$user) {
        //     throw new SuccessResponseException(__('message.location_not_found'), Response::HTTP_OK);
        // }
        return $this->model;
    }

    /**
     * Return the user resource with a success response.
     *
     * @return UserResource The user resource with a success response.
     */
    public function successResponse(): UserResource
    {
        return (new UserResource($this->model))
            ->additional(['message' => __('message.user_created')]);
    }
}
