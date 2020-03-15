<?php

namespace App\Transformers;

use App\Product;
use League\Fractal\TransformerAbstract;

class ProductTransformer extends TransformerAbstract
{
    
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(Product $product)
    {
        return [
            'identifier' => $product->id,
            'title'      => $product->name,
            'details'   => $product->description,
            'quantity'  => $product->quantity,
            'situation' => $product->status,
            'picture'   => url('public/images/'.$product->image),
            'seller' =>    $product->seller_id,
            'creationDate' => $product->created_at,
            'lastChangedDate' => $product->updated_at,
            'deletedDate'   =>  $product->deleted_at,
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
