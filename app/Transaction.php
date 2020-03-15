<?php

namespace App;

use App\Transformers\TransactionTransformer;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable=[
        'quantity',
        'buyer_id',
        'product_id'
    ];

    public $transformer = TransactionTransformer::class;
    
    public function buyer()
    {
        return $this->belongsTo(Buyer::class);
    }

    public function product(){
        return $this->belongsTo(Product::class);    
    }
}
