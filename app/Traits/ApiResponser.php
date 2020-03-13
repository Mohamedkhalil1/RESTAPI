<?php 

namespace App\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

trait ApiResponser{
    private function successResponse($data ,$code){

        return response()->json(['data' => $data] , $code);
    }

    protected function errorResponse($message,$code){
        return response()->json(['message' => $message , 'code' => $code] , $code);
    }

    protected function showAll(Collection $collection , $code=200){
       return $this->successResponse($collection, $code);
    }

    protected function showOne(Model $model , $code=200){
       return $this->successResponse($model,$code);
    } 

    protected function getMessage($message,$code=200){
        return $this->successResponse(['message' => $message],$code);
    }
}

?>