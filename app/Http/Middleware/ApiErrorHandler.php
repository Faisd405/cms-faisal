<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;

class ApiErrorHandler
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        try {
            return $next($request);
        } catch (ValidationException $e) {
            return $this->handleValidationException($e);
        } catch (ModelNotFoundException $e) {
            return $this->handleModelNotFoundException($e);
        } catch (HttpException $e) {
            return $this->handleHttpException($e);
        } catch (\Exception $e) {
            return $this->handleGenericException($e, $request);
        }
    }

    protected function handleValidationException(ValidationException $e): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => 'Validation failed',
            'error' => [
                'code' => 'VALIDATION_ERROR',
                'details' => $e->errors()
            ]
        ], 422);
    }

    protected function handleModelNotFoundException(ModelNotFoundException $e): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => 'Resource not found',
            'error' => [
                'code' => 'RESOURCE_NOT_FOUND',
                'details' => 'The requested resource could not be found'
            ]
        ], 404);
    }

    protected function handleHttpException(HttpException $e): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => $e->getMessage() ?: 'HTTP Error',
            'error' => [
                'code' => 'HTTP_ERROR',
                'status_code' => $e->getStatusCode()
            ]
        ], $e->getStatusCode());
    }

    protected function handleGenericException(\Exception $e, Request $request): JsonResponse
    {
        // Log the error
        \Log::error('API Exception', [
            'exception' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'trace' => $e->getTraceAsString(),
            'request' => [
                'url' => $request->fullUrl(),
                'method' => $request->method(),
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'params' => $request->all()
            ]
        ]);

        $message = config('app.debug') ? $e->getMessage() : 'An internal error occurred';

        return response()->json([
            'success' => false,
            'message' => 'Internal server error',
            'error' => [
                'code' => 'INTERNAL_ERROR',
                'details' => $message
            ]
        ], 500);
    }
}
