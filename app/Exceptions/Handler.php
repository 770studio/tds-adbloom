<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param Throwable $exception
     * @return void
     *
     * @throws Throwable
     */
    public function report(Throwable $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param Request $request
     * @param Throwable $exception
     * @return Response
     *
     * @throws Throwable
     */
    public function render($request, Throwable $exception)
    {
        return parent::render($request, $exception);
    }

    /**
     * Register the exception handling callbacks for the application.
     *
     *
     */

    public function register()
    {
        $this->renderable(function (Throwable $e, Request $request) {
            if ($request->is('api/*', 'redirect/*')) {
                return response()->json([
                    'status' => 'error',
                    'errorMessage' => $e instanceof BreakingException
                        ? $e->getMessage()  //Str::substr($e->getMessage(), 0, 50) . '...',
                        : 'unexpected error occurred'
                ], 422);
            }

        });
    }
}
