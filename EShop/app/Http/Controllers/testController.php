<?php

namespace App\Http\Controllers;

use App\Http\lib\zarinpal;
use Illuminate\Http\Request;

class testController extends Controller
{
    public function add_order(Request $request)
    {

        $order = new zarinpal();
        $res = $order->pay($request->price,"myroxo24@gmail.com","0912111111");
//        return redirect('https://www.zarinpal.com/pg/StartPay/' . $res);
        return redirect('https://sandbox.zarinpal.com/pg/StartPay/' . $res);

    }

    public function order(Request $request){

        $MerchantID = '39fd90bb-2e45-42cc-8b30-d87f577e8f48';
        $Authority =$request->get('Authority') ;

        //ما در اینجا مبلغ مورد نظر را بصورت دستی نوشتیم اما در پروژه های واقعی باید از دیتابیس بخوانیم
        $Amount = 100;
        if ($request->get('Status') == 'OK') {
//            $client = new nusoap_client('https://www.zarinpal.com/pg/services/WebGate/wsdl', 'wsdl');
            $client = new \nusoap_client('https://sandbox.zarinpal.com/pg/services/WebGate/wsdl', 'wsdl');
            $client->soap_defencoding = 'UTF-8';

            //در خط زیر یک درخواست به زرین پال ارسال می کنیم تا از صحت پرداخت کاربر مطمئن شویم
            $result = $client->call('PaymentVerification', [
                [
                    //این مقادیر را به سایت زرین پال برای دریافت تاییدیه نهایی ارسال می کنیم
                    'MerchantID'     => $MerchantID,
                    'Authority'      => $Authority,
                    'Amount'         => $Amount,
                ],
            ]);
            return $result['Status'];
            if ($result['Status'] == 100) {
                return 'پرداخت با موفقیت انجام شد.';

            } else {
                return 'testtt';
                return 'خطا در انجام عملیات';
            }
        }
        else
        {
            return 'خطا در انجام عملیات';
        }

    }
}
