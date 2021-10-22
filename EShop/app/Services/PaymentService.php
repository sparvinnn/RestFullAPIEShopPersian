<?php


namespace App\Services;


use App\Models\Product;

class PaymentService
{
    private $correspond         = true;
    private $confirm_products   = [];
    /**
     * get package price amount per lesson
     * price + 7000 Toman
     * @param $products
     * @return int
     */
    public function getProductsPriceToUser($products)
    {
        if ($products == null) return -1;
        foreach ($products as $product){
            $price = Product::query()->where('id', $product.id)->first()['price'];
            if($product.price != $price){
                $this->correspond = false;
            }
            array_push($this->confirm_products, [$product->id, $price]);
        }
    }
}
