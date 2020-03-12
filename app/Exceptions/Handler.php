<?php

namespace App\Exceptions;

use App\Traits\ApiResponser;
use BadMethodCallException;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Handler extends ExceptionHandler
{
    use ApiResponser;
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
            return $this->errorResponse("Does exist any {$model} with this specified identiticator",404);
        } 

        if($exception instanceof AuthenticationException){
            return $this->unauthenticated($request,$exception);
        }

        if($exception instanceof AuthorizationException){
            return $this->errorResponse($exception->getMessage(),409);
        }

        if($exception instanceof NotFoundHttpException){
            return $this->errorResponse('The Specified Url cannot be found',404);
        }

        if($exception instanceof MethodNotAllowedHttpException){
            return $this->errorResponse('the specified method you for request is invalid',405);
        }

        if($exception instanceof ValidationException){
           return $this->convertValidationExceptionToResponse($exception,$request);
        }
        if($exception instanceof BadRequestHttpException){
            return $this->errorResponse('Bad request does not exist' ,$exception->getStatusCode());
        }

        if($exception instanceof BadMethodCallException){
            return $this->errorResponse('Method request does not exist' ,404);
        }

        if($exception instanceof HttpException){
            return $this->errorResponse($exception->getMessage(),$exception->getStatusCode());
        }

        if($exception instanceof QueryException){
            $errorCode = $exception->errorInfo[1];
            if($errorCode === 1451){
                return $this->errorResponse('Cannot remove this resource permanetly . It is related with any other resources',409);
            }
        }

        if(config('app.debug')){
            return parent::render($request, $exception);
        }
        return $this->errorResponse('unexcepted exception',500);
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
