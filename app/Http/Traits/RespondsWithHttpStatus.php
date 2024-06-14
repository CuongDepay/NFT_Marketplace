<?php

namespace App\Http\Traits;

trait RespondsWithHttpStatus
{
    protected function success($message, $data = [], $status = 200)
    {
        if ($data) {
            return response()->json([
                'success' => true,
                'message' => $message,
                'payload' => $data
            ], $status);
        } else {
            return response()->json([
                'success' => true,
                'message' => $message,
            ], $status);
        }
    }

    protected function failure($message, $status = 500)
    {
        return response()->json([
            'success' => false,
            'message' => $message,
        ], $status);
    }
}
