<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class ApiController extends Controller
{
    
    private function successResponse($data ,$code){

        return response()->json(['data' => $data] , $code);
    }

    protected  function errorResponse($message,$code){
        return response()->json(['message' => $message , 'code' => $code] , $code);
    }

    protected function showAll(Collection $collection , $code=200){
       return $this->successResponse(['data' => $collection], $code);
    }

    protected function showOne(Model $model , $code=200){
       return $this->successResponse(['data'=>$model],$code);
    } 
}
