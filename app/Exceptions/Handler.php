<?php

namespace App\Exceptions;

use ErrorException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
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
            if($e instanceof ErrorException ){
                return response()->json([
                    'message'=>'رکورد مورد نظر یافت نشد'
                ]);
            }
            if ($e instanceof ModelNotFoundException){
                return response()->json([
                    'message'=>'رکورد مورد نظر یافت نشد'
                ]);
            }
        });
    }
}
