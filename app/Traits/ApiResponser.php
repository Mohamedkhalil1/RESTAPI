<?php 

namespace App\Traits;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

trait ApiResponser{
    private function successResponse($data ,$code){

        return response()->json($data , $code);
    }

    protected function errorResponse($message,$code){
        return response()->json(['message' => $message , 'code' => $code] , $code);
    }

    protected function showAll(Collection $collection , $code=200){
        if($collection->isEmpty()){
                return $this->successResponse(['data' => $collection],$code);
            }
        $transformer = $collection->first()->transformer;
        $collection = $this->filterData($collection,$transformer);
        $collection = $this->sortData($collection,$transformer);
        $collection= $this->paginate($collection);
        $collection = $this->transformData($collection,$transformer);
        $collection = $this->cacheResponse($collection);
        return $this->successResponse(['data' => $collection], $code);
    }

    protected function showOne(Model $instance , $code=200){
       $transformer = $instance->transformer;
       $instance = $this->transformData($instance,$transformer);
       return $this->successResponse(['data' => $instance],$code);
    } 

    protected function getMessage($message,$code=200){
        return $this->successResponse(['message' => $message],$code);
    }

    protected function transformData($data,$transformer)
    {
        $transformation = fractal($data,new $transformer);

        return $transformation->toArray()['data'];
    }

    private function filterData(Collection $collection , $transformer){
        foreach(request()->query() as $query=>$value){
            $attribute = $transformer::originAttribute($query);
            if(isset($attribute,$value)){
                $collection = $collection->where($attribute,$value);
            }
        }
        return $collection;
    }
    private function sortData(Collection $collection,$transformer)
    {
        if(request()->has('sort_by')){
            $attribute = $transformer::originAttribute(request()->get('sort_by'));
            $collection = $collection->sortBy->{$attribute};
        }
        return $collection;
    }

    private function paginate(Collection $collection){

        $rules = [
            'per_page' => 'integer|min:2|max:50',
        ];
        request()->validate($rules);
        $perPage =15;

        if(request()->has('per_page')){
            $perPage = (int) request()->per_page;
        }
        $page = LengthAwarePaginator::resolveCurrentPage();
        
        $result = $collection->slice(($page-1)*$perPage,$perPage)->values();
        $paginated= new  LengthAwarePaginator($result,$collection->count(),$perPage,$page,[
            'path' => LengthAwarePaginator::resolveCurrentPath()
        ]);
            // for another attribute as sort_by , filter 
        $paginated->appends(request()->all());    
        return $paginated;
    }

    private function cacheResponse($data){
        $url = request()->url();
        
        return Cache::remember($url, 30/60, function()use($data){
            return $data;
        });
    }
}

?>