<?php

namespace App\Exceptions;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;
use Illuminate\Http\Request;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
        $this->renderable(function (\Exception $e, Request $request) {
            if ($e instanceof ApiException) {
                return response()->json([
                    'errors' => $e->errors(),
                ], $e->getStatusCode());
            }

            if ($e instanceof ValidationException) {
                return response()->json([
                    'errors' => [
                        "code" => "VALIDATION_ERROR",
                        "message" => $e->errors(),
                    ],
                ], $e->status);
            }

            if (!config('app.debug')) {
                if ($e instanceof HttpException) {
                    return response()->json([
                        'errors' => [
                            "code" => "HTTP_ERROR",
                            "message" => $e->getMessage(),
                        ],
                    ], $e->getStatusCode());
                }

                return response()->json([
                    'errors' => [
                        "code" => "SERVER_ERROR",
                        "message" => "Unexpected server error",
                    ],
                ], 500);
            }
        });
    }
}
