<?php

namespace App\Transformers;

use App\Transaction;
use League\Fractal\TransformerAbstract;

class TransactionTransformer extends TransformerAbstract
{
    public function transform(Transaction $transaction)
    {
        return [
            'identifier' => (int)$transaction->id,
            'quantity'   => (int)$transaction->quantity,
            'buyer'      => (int)$transaction->buyer_id,
            'product'    => (int)$transaction->product_id,
            'creationDate' => $transaction->created_at,
            'lastCHangeDate' => $transaction->updated_at,
            'deletedDate'   => $transaction->deleted_at ? $transaction->deleted_at : null
        ];
    }

    public static function originAttribute($index){
        $attrubites = [
            'identifier' => 'id',
            'quantity'   => 'quantity',
            'buyer'      => 'buyer_id',
            'product'    => 'product_id',
            'creationDate' => 'created_at',
            'lastCHangeDate' => 'updated_at',
            'deletedDate'   => 'deleted_at' 
        ];
        return isset($attrubites[$index]) ? $attrubites[$index] : null; 
    }
}
