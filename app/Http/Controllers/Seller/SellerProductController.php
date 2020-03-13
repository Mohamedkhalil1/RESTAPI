<?php

namespace App\Http\Controllers\Seller;

use App\Seller;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use App\Product;
use App\User;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpKernel\Exception\HttpException;

class SellerProductController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Seller $seller)
    {
        //
        $products = $seller->products;
        return $this->showAll($products); 
    }

    public function store(Request $request , User $seller)
    {
        $rules = [
            'name' => 'required',
            'description' => 'required',
            'quantity' => 'required|numeric|min:1',
            'image'    => 'required|image'
        ];
        $this->validate($request,$rules);
        $params = $request->all();
        $params['status'] = Product::UNAVAILABLE_PRODUCT;
        $params['image'] = $request->image->store('');
        $params['seller_id']=$seller->id;
        $product = Product::create($params);

        return $this->showOne($product);
    }

    public function update(Request $request , Seller $seller , Product $product)
    {
        $rules = [
            'quantity' => 'integer|min:1',
            'image' => 'image',
            'status' => 'in:'.Product::AVAILABLE_PRODUCT. ','. Product::UNAVAILABLE_PRODUCT
        ];
        $this->validate($request,$rules);

        $this->checkSeller($seller,$product);

        $product->fill($request->only([
            'name','description','quantity'
        ]));

        
        if($request->has('status')){
            $product->status = $request->status;
            if($product->isavailable() && $product->categories()->first() === null){
                return $this->errorResponse('an active product must have at least one category',409);
            }
        }

        if($request->hasFile('image')){
            Storage::delete($product->image);
            $product->image = $request->image->store('');
        }
        
        if($product->isClean()){
            return $this->errorResponse('you need to specify a different value to update',422);
        }

        return $this->showOne($product);
    }

    public function destroy(Seller $seller , Product $product)
    {
        $this->checkSeller($seller,$product);
        $product->delete();
        Storage::delete($product->image);
        return $this->showOne($product);
    }

    private function checkSeller(Seller $seller ,Product $product){

        if($seller->id != $product->seller_id){
            throw new HttpException(422,'the specified seller is not the actual seller of the product');
        }
    }

}
