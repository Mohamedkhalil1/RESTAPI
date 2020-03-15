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
        if($collection->isEmpty()){
                return $this->successResponse(['data' => $collection],$code);
            }
        $transformer = $collection->first()->transformer;
        $collection = $this->sortData($collection,$transformer);
        $collection = $this->transformData($collection,$transformer);
        return $this->successResponse($collection, $code);
    }

    protected function showOne(Model $instance , $code=200){
       $transformer = $instance->transformer;
       $instance = $this->transformData($instance,$transformer);
       return $this->successResponse($instance,$code);
    } 

    protected function getMessage($message,$code=200){
        return $this->successResponse(['message' => $message],$code);
    }

    protected function transformData($data,$transformer)
    {
        $transformation = fractal($data,new $transformer);
        return $transformation->toArray();
    }

    private function sortData(Collection $collection,$transformer)
    {
        if(request()->has('sort_by')){
            $attribute = $transformer::originAttribute(request()->get('sort_by'));
            $collection = $collection->sortBy->{$attribute};
        }
        return $collection;
    }
}

?>