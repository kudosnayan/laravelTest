<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SuccessResponseException extends Exception
{
    /**
     * Report the exception.
     */
    public $customData = [];

    public function report(): void
    {
        // ...
    }

    /**
     * Render the exception into an HTTP response.
     */
    public function render(Request $request)
    {
        $responseData = [
            'data' => $this->customData['location'] ?? null, // Include the location data here
            'status' => 'success',
            'message' => $this->message,
        ];
        if (! empty($this->customData) && $this->customData['location_status'] != null) {
            unset($this->customData['location']);
            $responseData = array_merge($responseData, $this->customData);
        }

        return new JsonResponse($responseData, $this->code);
    }
}
