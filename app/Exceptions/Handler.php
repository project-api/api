<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\Debug\Exception\FlattenException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        // \Illuminate\Auth\AuthenticationException::class,
        // \Illuminate\Auth\Access\AuthorizationException::class,
        \Symfony\Component\HttpKernel\Exception\HttpException::class,
        // \Illuminate\Database\Eloquent\ModelNotFoundException::class,
        // \Illuminate\Session\TokenMismatchException::class,
        // \Illuminate\Validation\ValidationException::class,
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
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
     public function render($request, Exception $e)
     {
        $exception = FlattenException::create($e);
        $statusCode = $exception->getStatusCode($exception);
        if($e instanceof \Illuminate\Database\Eloquent\ModelNotFoundException){
          $statusCode = 404;
        }
        if(get_class($e) == "Symfony\\Component\\Debug\\Exception\\FatalThrowableError") {
          $statusCode = 400;
        }
        switch($statusCode){
          case 404:
            $message =  "Not Found";
            break;
          case 400:
            $message = "Bad Request";
            break;
          case 405:
            $message = "Method Not Allowed";
            break;
          default:
            $message = "Internal Server Error";
        }
        $data = array(
          'error' => $message,
          'status' => $statusCode,
          //'exeception' => get_class($e)
        );
        return response()->json($data, $statusCode);
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

        return redirect()->guest(route('login'));
    }
}
