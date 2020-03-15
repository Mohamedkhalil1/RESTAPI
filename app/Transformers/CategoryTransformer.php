<?php

namespace App\Transformers;

use App\Category;
use League\Fractal\TransformerAbstract;

class CategoryTransformer extends TransformerAbstract
{
    
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(Category $category)
    {
        return [
            'identifier' => $category->id,
            'title' => $category->name,
            'details' => $category->description,
            'creationDate' => $category->created_at,
            'lastChangeDate'=> $category->updated_at,
            'deletedDate'   => ($category->deleted_at) ? $category->deleted_at : null,
        ];
    }

    public static function originAttribute ($index){
        $attrubites = [
            'idenifier' => 'id',
            'title'      => 'name',
            'details'     => 'description',
            'quantity' => 'quantity',
            'situation' => 'status',
            'picture'   => 'image',
            'seller'    => 'seller_id',
            'creationDate' => 'created_at',
            'LastChangeDate' => 'updated_at',
            'deletedDate' => 'daleted_at'
        ];

        return isset($attrubites['index']) ? $attrubites['index'] : null ;
    }
    
}
