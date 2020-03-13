<?php

namespace App\Http\Controllers\Product;

use App\Buyer;
use App\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use App\Transaction;
use Illuminate\Support\Facades\DB;

class ProductBuyerTransactionController extends ApiController
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request,Product $product,Buyer $buyer)
    {
        $rules = [
            'quantity' => 'required|integer|min:1'
        ];

        $this->validate($request,$rules);

        if($buyer->id === $product->seller_id){
            return $this->errorResponse('the buyer should be different from the seller',409);
        }

        if(!$buyer->isVerified()){
            return $this->errorResponse('buyer must be a verified user', 409);
        }

        if(!$product->seller->isVerified()){
            return $this->errorResponse('seller must be a verified user',409);
        }

        if(!$product->isavailable()){
            return $this->errorResponse('product is not avaiable',409);
        }

        $product->quantity -= $request->quantity;
        $product->save();

        $tranaction = Transaction::create([
            'quantity' => $request->quantity,
            'product_id' => $product->id,
            'buyer_id' => $buyer->id
        ]);
        return $this->showOne($tranaction);
    }

}
