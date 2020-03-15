<?php

namespace App\Transformers;

use App\Seller;
use League\Fractal\TransformerAbstract;

class SellerTransformer extends TransformerAbstract
{
    public function transform(Seller $seller)
    {
        return [
            'identifier' => $seller->id,
            'name'       => $seller->name,
            'Email'      => $seller->email,
            'isVerified' => (int)$seller->verified,
            'createdDate' => $seller->created_at,
            'lastChangeDate' => $seller->updated_at,
            'deletedDate'   => $seller->deleted_at
        ];
    }

    public static function originAttribute($index){
        $attrubites = [
            'idenifier' => 'id',
            'Name'      => 'name',
            'Email'     => 'email',
            'isVerified' => 'verified',
            'creationDate' => 'created_at',
            'LastChangeDate' => 'updated_at',
            'deletedDate' => 'daleted_at'
        ];

        return isset($attrubites['index']) ? $attrubites['index'] : null ;
    }
}
