<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;

/**
 * Class ResponseHelper
 *
 * @method JsonResponse successResponse($data, $message = null, $statusCode = 200)
 * @method JsonResponse errorResponse($data, $message = null, $statusCode = 400)
 * @method mixed dynamicSuccessResponse($view, $data, $typeView = 'blade', $message = null, $statusCode = 200)
 * @method mixed dynamicErrorResponse($view, $data, $typeView = 'blade', $message = null, $statusCode = 400)
 */
trait ResponseHelper
{
    private function responseJson(
        bool $success,
        ?string $message,
        int $statusCode,
        mixed $data = []
    ) {
        return response()->json([
            'success' => $success,
            'message' => $message,
            'data' => $data,
        ], $statusCode);
    }

    public function successResponse(
        mixed $data,
        ?string $message = null,
        int $statusCode = 200
    ) {
        return $this->responseJson(true, $message, $statusCode, $data);
    }

    public function errorResponse(
        mixed $data,
        ?string $message = null,
        int $statusCode = 400
    ) {
        return $this->responseJson(false, $message, $statusCode, $data);
    }

    public function dynamicSuccessResponse($view, $data, $typeView = 'blade', $message = null, $statusCode = 200)
    {
        if (request()->wantsJson()) {
            return $this->successResponse($data, $message, $statusCode);
        }

        if ($typeView === 'inertia' && class_exists('Inertia\Inertia')) {
            return inertia($view, $data)->with('success', $message);
        }

        if ($typeView === 'redirect') {
            return redirect()->route($view)->with('success', $message);
        }

        return view($view, $data)->with('success', $message);
    }

    public function dynamicErrorResponse($view, $data, $typeView = 'blade', $message = null, $statusCode = 400)
    {
        if (request()->wantsJson()) {
            return $this->errorResponse($data, $message, $statusCode);
        }

        if ($typeView === 'inertia' && class_exists('Inertia\Inertia')) {
            return inertia($view, $data)->with('error', $message);
        }

        if ($typeView === 'redirect') {
            return redirect()->route($view)->with('error', $message);
        }

        return view($view, $data)->with('error', $message);
    }
}
