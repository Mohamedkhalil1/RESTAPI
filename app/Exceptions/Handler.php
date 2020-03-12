<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

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
    public function render($request, Exception $exception)
    {

        if($exception instanceof ModelNotFoundException){
            $model = strtolower(class_basename($exception->getModel()));
            return response()->json(['Error' => "Does exist any {$model} with this specified identiticator",'code'=>'404'],404);
        } 

        if($exception instanceof AuthenticationException){
            return $this->unauthenticated($request,$exception);
        }

        if($exception instanceof AuthorizationException){
            return response()->json(['error' => $exception->getMessage(),'code' => 409],409);
        }

        if($exception instanceof NotFoundHttpException){
            return response()->json(['error' => 'The Specified Url cannot be found ' ,'code' => 404],404);
        }

        if($exception instanceof MethodNotAllowedHttpException){
            return response()->json(['error' => 'the specified method you for request is invalid','code' => 405],405);
        }

        if($exception instanceof ValidationException){
           return $this->convertValidationExceptionToResponse($exception,$request);
        }

        if($exception instanceof HttpException){
            return response()->json(['data' => $exception->getMessage(),'code' => $exception->getStatusCode()],$exception->getStatusCode());
        }

        if($exception instanceof QueryException){
            $errorCode = $exception->errorInfo[1];
            if($errorCode === 1451){
                return response()->json(['error' => 'Cannot remove this resource permanetly . It is related with any other resources','code'=>409],409);
            }
        }

        if(config('app.debug')){
            return parent::render($request, $exception);
        }
        return response()->json(['error'=>'unexcepted exception ','code' =>500],500);
    }

    /**
     * Convert an authentication exception into a response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Auth\AuthenticationException  $exception
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function unauthenticated($request, AuthenticationException $exception)
    {
       return response()->json(['error' => 'unauthenticated','code'=>401],401);
    }

    /**
     * Create a response object from the given validation exception.
     *
     * @param  \Illuminate\Validation\ValidationException  $e
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function convertValidationExceptionToResponse(ValidationException  $e, $request)
    {
        $errors = $e->validator->errors()->getMessages();
        return response()->json(['data' => $errors , 'code' => 422],422);
    }
}
