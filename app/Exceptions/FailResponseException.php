<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FailResponseException extends Exception
{
    /**
     * Report the exception.
     */
    public function report(): void
    {
        // ...
    }

    /**
     * Render the exception into an HTTP response.
     */
    public function render(Request $request)
    {
        if ($this->code != 422) {
            return new JsonResponse(['status' => 'fail', 'message' => $this->message, 'status_code' => $this->code], 200);

        } else {
            return new JsonResponse(['status' => 'fail', 'message' => $this->message, 'status_code' => $this->code], $this->code);

        }
    }
}
