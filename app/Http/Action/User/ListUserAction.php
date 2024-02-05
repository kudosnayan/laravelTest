<?php

namespace App\Http\Action\User;

use App\Exceptions\SuccessResponseException;
use App\Http\Action\User\Base\UserAction;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

class ListUserAction extends UserAction
{

    /**
     * Handle user registration and return the created user model.
     *
     * @return Model The created user model.
     */
    public function handle(): Model
    {
        $this->model = $this->modelClass::get();
            
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
        $perPage = Arr::get($this->data, 'paginate_per_page', env('DEFAULT_PAGINATE_PER_PAGE'));

        return UserResource::collection($this->model->paginate($perPage)->onEachSide(env('ON_EACH_SIDE')))
            ->additional(['message' => __('message.list_location')]);
    }
}
