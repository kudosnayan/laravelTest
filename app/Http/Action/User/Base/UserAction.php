<?php

namespace App\Http\Action\User\Base;

use App\Models\User;
use App\Support\Traits\Executable;

abstract class UserAction extends Executable
{
    /**
     * The model instance.
     *
     * @var model
     */
    protected $model;

    /**
     * The data used to perform the action.
     *
     * @var array
     */
    protected $data;

    /**
     * The model class associated with this action.
     *
     * @var User
     */
    protected string $modelClass = User::class;

    /**
     * Create a new UserAction instance.
     *
     * @param  mixed  $data
     * @param  mixed  $model
     */
    public function __construct($data = null, $model = null)
    {
        $this->data = $data ?? [];
        $this->model = $model ?? new $this->modelClass();
    }
}
