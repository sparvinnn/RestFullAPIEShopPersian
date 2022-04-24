<?php

namespace App\Http\Controllers;

use App\Http\lib\zarinpal;
use App\Models\Category;
use App\Models\Color;
use App\Models\Order;
use App\Models\Payment;
use App\Models\PaymentProduct;
use App\Models\Product;
use App\Models\ProductProperty;
use App\Models\Size;
use App\Services\PaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class testController extends Controller
{
    private $amount=0;

    public function mytest(){
        
        $client = new \GuzzleHttp\Client(); 
        $res    = $client->request('GET', env('API_REQUEST_URL').
        'itemparent?count=2068',  [
            'headers' => [
                'WEB_TOKEN' => ['727c8e6b-e34f-49fe-9abe-59d5e4301e74']
            ],
        ]);
    
        //decode string response to json format
        $data = json_decode($res->getBody())->Value;
        
        foreach($data as $item){
            return $item->ItemParentID;
            print('test | ');
            print($item->ItemCurrentSelPrice);
            $product = Product::create([
                'name'                   => $item->ItemName, 
                'sell_price'             => $item->ItemCurrentSelPrice?? 10,
                'category_id'            => Category::where('category_code_giv', $item->ItemCategory->CategoryCode)->first()['id'],
                'branch_id'              => 1,   
                'inventory_number'       => 1,    
                'last_date_giv'          => $item->LastDate,
                'item_code_giv'          => $item->ItemCode,
                'is_active'              => $item->IsActive,
                'item_group_giv'         => $item->ItemGroup,
                'item_parent_id_giv'     => $item->ItemParentID
            ]);

            
            return 'test';
            $res    = $client->request('GET', env('API_REQUEST_URL').
                'itemqoh?inputcode='.$product['item_code_giv'],  [
                    'headers' => [
                        'WEB_TOKEN' => ['727c8e6b-e34f-49fe-9abe-59d5e4301e74']
                    ],
                ]);
            
            //decode string response to json format
            $data = json_decode($res->getBody())->Value->Table->TableData->Items;

            $sum_qoh = 0;
            foreach($data as $value){

                $color = Color::whfirstOrNew([
                    'name' => $value->ItemColorName
                ]);

                $size = Size::whfirstOrNew([
                    'name' => $value->ItemSizeDesc
                ]);

                ProductProperty::create([
                    'product_id' => $product->id,
                    'size'  => $size->id,//اندازه
                    'color' => $color->id,//رنگ
                ]);
                $sum_qoh += $item->QOH;
            }

            $product['inventory_number'] = $sum_qoh;
            $product['sell_price'] = $data->SellPrice;
            $product->save();
        
        }
    }

    public function test(Request $request){

        $change_price = [];
        $products = json_decode($request->list);
//        $products = $request->list;

        foreach ($products as $item) {
//            $item = json_decode($item);
            $temp = Product::query()
                ->where('id', $item->id)
//                ->where('id', $item['id'])
                ->first()['price'];
//            if($item['price'] == $temp) {
            if($item->price == $temp) {
//                $this->amount += $item['price'] * $item['num'];
                $this->amount += $item->price * $item->num;
                continue;
            }
//            $item['price'] = $temp;
            $item->price = $temp;
            array_push($change_price, $item);
        }

        if(count($change_price)>0)
            return $change_price;
        else{
            $payment = Payment::create([
                'user_id' => Auth::id(),
                'amount'  => $this->amount
            ]);
            foreach ($products as $item) {
//                PaymentProduct::create([
//                    'product_id' => $item['id'],
//                    'payment_id' => $payment['id'],
//                    'num'        => $item['num']
//                ]);
                PaymentProduct::create([
                    'product_id' => $item->id,
                    'payment_id' => $payment['id'],
                    'num'        => $item->num
                ]);
            }
        }

        $order = new zarinpal();
        $res = $order->pay($request->price,"sparvinnn@gmail.com","09117158276");
        return redirect('https://sandbox.zarinpal.com/pg/StartPay/' . $res);
    }

    public function order(Request $request){
        $MerchantID = '39fd90bb-2e45-42cc-8b30-d87f577e8f48';
        $Authority =$request->get('Authority') ;

        //ما در اینجا مبلغ مورد نظر را بصورت دستی نوشتیم اما در پروژه های واقعی باید از دیتابیس بخوانیم
        $amount = 0;
        $payment = Payment::query()
//            ->where('user_id', Auth::id())->latest();
            ->where('user_id', 1)->latest()->first();
        $products = PaymentProduct::query()
            ->where('payment_id', $payment->id)
            ->get();

        foreach ($products as $item){
            $amount += $item->num * Product::query()->where('id', $item->product_id)->first()['price'];
        }

        if ($request->get('Status') == 'OK') {

//            $client = new \nusoap_client('https://www.zarinpal.com/pg/services/WebGate/wsdl', 'wsdl');
            $client = new \nusoap_client('https://sandbox.zarinpal.com/pg/services/WebGate/wsdl', 'wsdl');
            $client->soap_defencoding = 'UTF-8';

            //در خط زیر یک درخواست به زرین پال ارسال می کنیم تا از صحت پرداخت کاربر مطمئن شویم

            $result = $client->call('PaymentVerification', [
                [
                    //این مقادیر را به سایت زرین پال برای دریافت تاییدیه نهایی ارسال می کنیم
                    'MerchantID'     => $MerchantID,
                    'Authority'      => $Authority,
                    'Amount'         => $amount,
                ],
            ]);

            Order::create([
                'user_id' => Auth::id(),
                'amount'  => $amount,
                'ref_id'  => $result['RefID']
            ]);

            if ($result['Status'] == 100) {
                return redirect('http://localhost:3000/dashboard/basket');
                return 'پرداخت با موفقیت انجام شد.';

            } else {
                return 'خطا در انجام عملیات';
            }
        }
        else
        {
            return 'خطا در انجام عملیات';
        }

    }

    public function add_order(Request $request)
    {

        $order = new zarinpal();
        $res = $order->pay($request->price,"myroxo24@gmail.com","0912111111");
//        return redirect('https://www.zarinpal.com/pg/StartPay/' . $res);
        return redirect('https://sandbox.zarinpal.com/pg/StartPay/' . $res);

    }
}
