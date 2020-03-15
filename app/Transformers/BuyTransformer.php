<?php

namespace App\Transformers;

use App\Buyer;
use League\Fractal\TransformerAbstract;

class BuyTransformer extends TransformerAbstract
{
    
    public function transform(Buyer $buyer)
    {
        return [
            'idenifier' => (int)$buyer->id,
            'Name'      => $buyer->name,
            'Email'     => $buyer->email,
            'isVerified' => (int)$buyer->verified,
            'creationDate' => $buyer->created_at,
            'LastChangeDate' => $buyer->updated_at,
            'deletedDate' => isset($buyer->daleted_at) ? (string)$buyer->deleted_at : null     
        ];
    }

    public static function originAttribute ($index){
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
