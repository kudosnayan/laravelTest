<?php

namespace App\Support\Traits;

use Exception;

abstract class Executable
{
    /**
     * Execute the action.
     *
     * @return mixed
     *
     * @throws Exception
     */
    public function execute()
    {
        try {
            // Check if the action is valid, either by calling the "validate" method or by skipping validation
            if ((method_exists($this, 'validate') && $this->validate()) || ! method_exists($this, 'validate')) {
                // Perform the action and store the resulting model instance
                $this->model = $this->handle();

                // Fire an event, if the action has an "fireEvent" method
                if (method_exists($this, 'fireEvent')) {
                    $this->fireEvent();
                }

                // Return a success response, if the action has a "successResponse" method
                return $this->successResponse();
            }
        } catch (Exception $exception) {
            // If an exception is thrown during the action, return a failure response or re-throw the exception
            if (method_exists($this, 'failResponse')) {
                return response($this->failResponse(), 500);
            }
            throw $exception;
        }
    }

    /**
     * Save or update the model.
     *
     * @return mixed
     */
    abstract protected function handle();
}
