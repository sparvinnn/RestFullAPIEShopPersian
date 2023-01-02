<?php

namespace App\Http\Controllers;

use App\Http\lib\zarinpal;
use App\Models\Order;
use App\Models\Payment;
use App\Models\PaymentProduct;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class paymentController extends Controller
{
    private $amount=0;

    public function start(Request $req){
        
        $data = array(
            "MerchantID" => "39fd90bb-2e45-42cc-8b30-d87f577e8f48",
            "Amount" => 1000,
            "CallbackURL" => env('FRONT_URL'). "/ecommerce/orders",
            "Description" => "خرید تست",
            "Metadata" => [ "email" => "sparvinnn@gmail.com","mobile"=>"09117158276"],
            );
        $jsonData = json_encode($data);
        $ch = curl_init('https://sandbox.zarinpal.com/pg/rest/WebGate/PaymentRequest.json');
        curl_setopt($ch, CURLOPT_USERAGENT, 'ZarinPal Rest Api v1');
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Content-Length: ' . strlen($jsonData)
        ));

        $result = curl_exec($ch);
        $err = curl_error($ch);
        $result = json_decode($result, true, JSON_PRETTY_PRINT);
        curl_close($ch);

        // return $result;
        
        if ($err) {
            echo "cURL Error #:" . $err;
        } else {
            if (empty($result['errors'])) {
                if ($result['Status'] == 100) {
                    header('Location: https://sandbox.zarinpal.com/pg/StartPay/' . $result['Authority']);
                }
            } else {
                echo'Error Code: ' . $result['errors']['code'];
                echo'message: ' .  $result['errors']['message'];

            }
        }

        return 'https://sandbox.zarinpal.com/pg/StartPay/' . $result['Authority'];
        return redirect('https://sandbox.zarinpal.com/pg/StartPay/' . $result['Authority']);
        
    }

    public function verify(Request $req){
        $Authority = $_GET['Authority'];
        $data = array("merchant_id" => "xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx", "authority" => $Authority, "amount" => 1000);
        $jsonData = json_encode($data);
        $ch = curl_init('https://api.zarinpal.com/pg/v4/payment/verify.json');
        curl_setopt($ch, CURLOPT_USERAGENT, 'ZarinPal Rest Api v4');
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Content-Length: ' . strlen($jsonData)
        ));

        $result = curl_exec($ch);
        $err = curl_error($ch);
        curl_close($ch);
        $result = json_decode($result, true);
        
        if ($err) {
            echo "cURL Error #:" . $err;
        } else {
            if ($result['data']['code'] == 100) {
                echo 'Transation success. RefID:' . $result['data']['ref_id'];
            } else {
                echo'code: ' . $result['errors']['code'];
                echo'message: ' .  $result['errors']['message'];
            }
        }
    }
    public function start1(Request $request){

        $change_price = [];

       $products = $request->list;

        foreach ($products as $item) {

            $temp = Product::query()
                ->where('id', $item['id'])
                ->first();
            if($item['price'] == $temp['price'] && $temp['inventory_number']>0) {
               $this->amount += $item['price'] * $item['num'];
                continue;
            }
            $item['price'] = $temp;
            array_push($change_price, $item);
        }


        if(count($change_price)>0)
            return response()->json([
                'status'=>false,
                'data'=>$change_price
            ]);

        else{
            $payment = Payment::create([
                'user_id' => Auth::id(),
                'amount'  => $this->amount
            ]);
            foreach ($products as $item) {
               PaymentProduct::create([
                   'product_id' => $item['id'],
                   'payment_id' => $payment['id'],
                   'num'        => $item['num']
               ]);
            }
        }

        $order = new zarinpal();
        $res = $order->pay($request->price,"sparvinnn@gmail.com","09117158276");
        return response()->json([
            'status'=>true,
            'data'=>'https://sandbox.zarinpal.com/pg/StartPay/' . $res
        ]);


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

        $amount = $products = PaymentProduct::query()
            ->where('payment_id', $payment->id)
            ->first()['amount'];

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

            return redirect('http://localhost:3000/dashboard/basket/register?status='.$result['Status'].'&RefID='.$result['RefID']);

            if ($result['Status'] == 100) {

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
