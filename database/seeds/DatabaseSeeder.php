<?php

use App\Category;
use App\Product;
use App\Transaction;
use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
      
       

        $userQuantity = 1000;
        $categoryQuantity=30;
        $productQuantity=1000;
        $transactionQuantity=1000;

        User::flushEventListeners();
        Category::flushEventListeners();
        Product::flushEventListeners();
        Transaction::flushEventListeners();
         
        factory(User::class,$userQuantity)->create();
        factory(Category::class,$categoryQuantity)->create();

        factory(Product::class,$productQuantity)->create()->each(function($product){
            $categories = Category::all()->random(mt_rand(1,5))->pluck('id');
            $product->categories()->attach($categories);
        });
        
        factory(Transaction::class,$transactionQuantity)->create();

    }
}
