<?php

namespace App\Exceptions;

use App\Integrations\EasyPost\Exceptions\EasyPostApiException;
use Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        \League\OAuth2\Server\Exception\AccessDeniedException::class,
        \League\OAuth2\Server\Exception\InvalidRequestException::class,
        \League\OAuth2\Server\Exception\InvalidCredentialsException::class,
        \Illuminate\Auth\AuthenticationException::class,
        \Illuminate\Auth\Access\AuthorizationException::class,
        \Symfony\Component\HttpKernel\Exception\HttpException::class,
        \Illuminate\Database\Eloquent\ModelNotFoundException::class,
        \Illuminate\Session\TokenMismatchException::class,
        \Illuminate\Validation\ValidationException::class,
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        parent::report($exception);

        /**
        if ($exception->getCode() == 500 ||
        ($this->isHttpException($exception) && $exception->getStatusCode() == 500) ||
        $exception instanceof EasyPostApiException)
        {
        parent::report($exception);
        }
         */
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {
        if (!config('app.debug') || $this->isHttpException($exception))
        {
            $response = [
                'message'           => $exception->getMessage(),
            ];

            $status                 = 500;

            //  HttpException checks
            if ($this->isHttpException($exception))
            {
                $status = $exception->getStatusCode();
                if ($status == 404)
                {
                    if (empty($response['message']))
                        $response['message'] = 'You are most likely trying access a route that does not exist. Check your spelling and syntax.';
                }
            }



            if (config('app.debug'))
            {
                $response['debug'] = [
                    'file' => $exception->getFile(),
                    'line' => $exception->getLine(),
                    'exception' => $exception->getTraceAsString()
                ];
            }

            return response()->json($response, $status);
        }

        return parent::render($request, $exception);
    }

    /**
     * Convert an authentication exception into an unauthenticated response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Auth\AuthenticationException  $exception
     * @return \Illuminate\Http\Response
     */
    protected function unauthenticated($request, AuthenticationException $exception)
    {
        if ($request->expectsJson()) {
            return response()->json(['error' => 'Unauthenticated.'], 401);
        }

        return redirect()->guest('login');
    }
}
