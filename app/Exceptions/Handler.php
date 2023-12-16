<?php

namespace App\Exceptions;

use ErrorException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

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

        });
        $this->renderable(function (MethodNotAllowedHttpException $e, $request) {
            return response()->json([
                'status' => 405,
                'message' => 'Method Not Allowed'
            ], 405);
        });
        $this->renderable(function (NotFoundHttpException $e, $request) {
            return response()->json([
                'status' => 404,
                'message' => 'The Route Not Found'
            ], 405);
        });
    }
}
