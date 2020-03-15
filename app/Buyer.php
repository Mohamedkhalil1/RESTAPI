<?php

namespace App;

use App\Scopes\BuyerScope;
use App\Transformers\BuyTransformer;

class Buyer extends User
{

    protected static function boot(){
        parent::boot();

        static::addGlobalScope(new BuyerScope);
    }

    public $transformer = BuyTransformer::class;
    
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
}
