<?php

namespace App\Exceptions;

use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Throwable;

class HandlerApiException extends Handler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [];

    /**
     * Report or log an exception.
     *
     * @param  Throwable  $exception
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
     * @param  Request  $request
     * @param  Throwable  $exception
     * @return JsonResponse
     *
     * @throws Throwable
     */
    public function render($request, Throwable $exception)
    {
        $parentException = parent::render($request, $exception);

        if($parentException->exception instanceof NotFoundHttpException)
            return response()->json([
                'status' =>  404,
                'message' => "Not Found Request",
            ], 404);

        if ($parentException->exception instanceof MethodNotAllowedHttpException)
            return response()->json([
                'status' =>  405,
                'message' => "Method Not Allowed",
            ], 405);

        if ($parentException->exception instanceof UnauthorizedHttpException)
            return response()->json([
                'status' => 401,
                'message' => $parentException->exception->getMessage(),
            ], 401);

        if ($exception instanceof ValidationException)
            return response()->json([
                'status' => $exception->status,
                'message' => $exception->getMessage(),
                'errors' => $exception->validator->errors()->all()
            ],$exception->status);

        if (isset($parentException->exception) && $parentException->exception->getCode() == 0)
            return response()->json([
                'status' => 500,
                'message' => $parentException->exception->getMessage()
            ], 500);

        if (!isset($parentException->exception) && $parentException instanceof JsonResponse)
            return response()->json([
                'status' => 500,
                'message' => $parentException->getData()->message
            ], 500);

        return response()->json([
            'status' =>  $parentException->exception->getCode(),
            'message' => $parentException->exception->getMessage(),
        ], $parentException->exception->getCode());
    }
}
