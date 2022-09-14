<?php

namespace Roxanne\LaravelBase\Common\Exception;

use Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\ValidationException;
use Roxanne\LaravelBase\Common\Exception\Exceptions\BusinessException;
use Symfony\Component\Debug\Exception\FatalThrowableError;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Roxanne\MicroApi\MicroApiRequestException;
use YiluTech\YiMQ\Exceptions\YiMqSubtaskPrepareException;
use YiluTech\YiMQ\Http\Controllers\YiMqController;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //BusinessException::class
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
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param \Exception $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Exception $exception
     * @return \Illuminate\Http\Response
     */

    public function render($request, Exception $e)
    {
        if (method_exists($e, 'render') && $response = $e->render($request)) {
            return Router::toResponse($request, $response);
        } elseif ($e instanceof Responsable) {
            return $e->toResponse($request);
        }

        $e = $this->prepareException($e);


        if ($e instanceof HttpResponseException) {
            return $e->getResponse();
        } elseif ($e instanceof AuthenticationException) {
            return $this->unauthenticated($request, $e);
        } elseif ($e instanceof ValidationException) {
            $status = 200;
            if (request()->route()->getActionName() == 'YiluTech\YiMQ\Http\Controllers\YiMqController@run')
                $status = 422;
            return response()->json([
                'code' => 422,
                'message' => 'validation_error',
                'data' => $e->validator->errors()->getMessages()
            ], $status);
        } elseif ($request->expectsJson() && $e instanceof NotFoundHttpException) {
            return response()->json([
                'message' => 'Sorry, the page you are looking for could not be found.',
            ], 404);
        } elseif ($e instanceof BusinessException) {  //业务错误
            return $e->getResponse();
        } elseif ($e instanceof YiMqSubtaskPrepareException) {
            if ($e->getStatusCode() == 400 || $e->getStatusCode() == 422) {
                return response()->json($e->getResult());
            }
            return $e->getResponse();
        } elseif ($e instanceof MicroApiRequestException) {
            return response()->json([
                'message' => $e->getMessage(),
                'exception' => get_class($e),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'body' => $e->getData()
            ], 502);
        }

        return $request->expectsJson()
            ? $this->prepareJsonResponse($request, $e)
            : $this->prepareResponse($request, $e);

    }

}
