<?php

namespace App\Http\Controllers;

use App\lib\zarinpal;
use App\Models\AdsPlan;
use App\Models\Balance;
use App\Models\Content;
use App\Models\Sell;
use App\Models\Transaction;
use App\Models\TransactionCharge;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class ZarinpalController extends Controller
{
    public function zarinPay($id, $mode = 'download',Request $request)
    {
//        $back_url = $_SERVER['HTTP_REFERER'];
        $back_url = $request->back_url;
        $user = (auth()->check()) ? auth()->user() : false;

        if (!$user)
            return Redirect::to('/user?redirect=/product/' . $id);

        if($mode == 'download'){

            $content = Content::with('metas')->where('mode', 'publish')->find($id);
            if (!$content)
                abort(404);
            if ($content->private == 1)
                $site_income = get_option('site_income_private');
            else
                $site_income = get_option('site_income');

            ## Vendor Group Percent
            $Vendor = User::with(['category'])->find($content->user_id);
            if(isset($Vendor) && isset($Vendor->category->commision) && ($Vendor->category->commision > 0)){
                $site_income = $site_income - $Vendor->category->commision;
            }
            ## Vendor Rate Percent
            if($Vendor){
                $Rates = getRate($Vendor->toArray());
                if($Rates){
                    $RatePercent = 0;
                    foreach ($Rates as $rate){
                        $RatePercent += $rate['commision'];
                    }

                    $site_income = $site_income - $RatePercent;
                }
            }

            $meta = arrayToList($content->metas, 'option', 'value');

            if ($mode == 'download')
                $Amount = $meta['price'];
            elseif ($mode == 'post')
                $Amount = $meta['post_price'];

            $Amount_pay = pricePay($content->id, $content->category_id, $Amount)['price'];
        }

        else if($mode=='promotion'){

            $content = AdsPlan::where('mode', 'publish')->find($id);
            if (!$content)
                abort(404);

            $site_income = get_option('site_income');

            ## Vendor Group Percent
            $Vendor = User::with(['category'])->find($content->user_id);
            if(isset($Vendor) && isset($Vendor->category->commision) && ($Vendor->category->commision > 0)){
                $site_income = $site_income - $Vendor->category->commision;
            }
            ## Vendor Rate Percent
            if($Vendor){
                $Rates = getRate($Vendor->toArray());
                if($Rates){
                    $RatePercent = 0;
                    foreach ($Rates as $rate){
                        $RatePercent += $rate['commision'];
                    }

                    $site_income = $site_income - $RatePercent;
                }
            }
            $Amount = $content->price;
            $Amount_pay = pricePay($content->id, $content->id, $Amount)['price'];
        }

        $Description = trans('admin.item_purchased') . $content->title . trans('admin.by') . $user['name']; // Required

        //$Amount_pay = 1000;
        try {
            $order = new zarinpal();
            $url  = "/user/balance/verifyBuy?content_id=".(string)$id."&site_income=". $site_income . "&Amount_pay=" . $Amount_pay . "&mode=" . $mode. "&back_url=" . $back_url;
            $res = $order->pay($Amount_pay,"","", $url);
//            return  redirect('https://www.zarinpal.com/pg/StartPay/' . $res);
            return  redirect('https://sandbox.zarinpal.com/pg/StartPay/' . $res);
        } catch (Exception $ex) {
            if (\Config::get('app.debug')) {
                \Session::put('error', 'Connection timeout');
                return redirect()->back();
            } else {
                \Session::put('error', 'Some error occur, sorry for inconvenient');
                return redirect()->back();
            }
        }
        // foreach ($payment->getLinks() as $link) {
        //     if ($link->getRel() == 'approval_url') {
        //         $redirect_url = $link->getHref();
        //         break;
        //     }
        // }



    }
    public function verifyBuy(Request $request){
        //return $request;

        $user = auth()->user();
        $type = "charge";

        $MerchantID = '68255607-b45b-4f9c-834a-2451d66c2ba0';
        $Authority =$request->get('Authority') ;

        // $Amount = $transaction->price;
        if ($request->get('Status') == 'OK') {
//            $client = new nusoap_client('https://www.zarinpal.com/pg/services/WebGate/wsdl', 'wsdl');
            $client = new nusoap_client('https://sandbox.zarinpal.com/pg/services/WebGate/wsdl', 'wsdl');
            $client->soap_defencoding = 'UTF-8';

            $result = $client->call('PaymentVerification', [
                [
                    'MerchantID'     => $MerchantID,
                    'Authority'      => $Authority,
                    'Amount'         => $request->Amount_pay,
                ],
            ]);

            if ($result['Status'] == 100) {
                if( $request->mode == 'download'){
                    $content = Content::with('metas')->where('mode', 'publish')->find($request->content_id);
                    $site_income = $request->site_income;
                    $res = $request->Authority;
                    $Amount_pay = $request->Amount_pay;
                    $mode = $request->mode;
                    $meta = arrayToList($content->metas, 'option', 'value');

                    if ($request->mode == 'download')
                        $Amount = $meta['price'];
                    elseif ($mode == 'post')
                        $Amount = $meta['post_price'];

                }elseif($request->mode == 'promotion'){
                    $content = AdsPlan::where('mode', 'publish')->find($request->content_id);
                    $site_income = $request->site_income;
                    $res = $request->Authority;
                    $Amount_pay = $request->Amount_pay;
                    $mode = $request->mode;

                    $Amount = $content->price;
                }

                \Session::put('zarinpal_payment_id', $res);
                $transaction = Transaction::create([
                    'buyer_id' => $user['id'],
                    'user_id' => $content->user_id,
                    'content_id' => $content->id,
                    'price' => $Amount_pay,
                    'price_content' => $Amount,
                    'mode' => 'pending',
                    'created_at' => time(),
                    'bank' => 'zarinpal',
                    'income' => $Amount_pay - (($site_income / 100) * $Amount_pay),
                    'authority' => $res,
                    'type' => $request->mode
                ]);

                Sell::insert([
                    'user_id' => $content->user_id,
                    'buyer_id' => $user['id'],
                    'content_id' => $content->id,
                    'type' => $request->mode,
                    'created_at' => time(),
                    'mode' => 'pay',
                    'transaction_id' => $transaction->id
                ]);

                $seller = User::with('category')->find($content->user_id);
                if($seller)
                    $seller->update(['income' => $seller->income + ((100 - $site_income) / 100) * $Amount_pay]);
                $buyer = User::find($user['id']);
                $buyer->update(['credit' => $user['credit'] - $Amount_pay]);

                Balance::create([
                    'title' => trans('admin.item_purchased') . $content->title,
                    'description' => trans('admin.item_purchased_desc'),
                    'type' => 'minus',
                    'price' => $Amount_pay,
                    'mode' => 'auto',
                    'user_id' => $buyer->id,
                    'exporter_id' => 0,
                    'created_at' => time()
                ]);
                if($seller)
                    Balance::create([
                        'title' => trans('admin.item_sold') . $content->title,
                        'description' => trans('admin.item_sold_desc'),
                        'type' => 'add',
                        'price' => ((100 - $site_income) / 100) * $Amount_pay,
                        'mode' => 'auto',
                        'user_id' => $seller->id,
                        'exporter_id' => 0,
                        'created_at' => time()
                    ]);
                Balance::create([
                    'title' => trans('admin.item_profit') . $content->title,
                    'description' => trans('admin.item_profit_desc') . $buyer->username,
                    'type' => 'add',
                    'price' => ($site_income / 100) * $Amount_pay,
                    'mode' => 'auto',
                    'user_id' => 0,
                    'exporter_id' => 0,
                    'created_at' => time()
                ]);

                ## Notification Center
                if($request->mode == 'download')
                    $product = Content::find($transaction->content_id);
                elseif($request->mode == 'promotion')
                    $product = AdsPlan::find($transaction->content_id);

                sendNotification(0, ['[c.title]' => $product->title], get_option('notification_template_buy_new'), 'user', $buyer->id);

                \Session::put('zarinpal_payment_id', $res);
                return redirect('/user/balance/log')->with('msg', trans('main.product_buy_success'));
                return 'OK';
                /** add payment ID to session **/
                if (isset($redirect_url)) {
                    /** redirect to paypal **/
                    return Redirect::away($redirect_url);
                }
                \Session::put('error', 'Unknown error occurred');


            } else {
                \Session::put('error', 'Unknown error occurred');
                // return 'NO';
            }
            return redirect()->back()->with('msg', trans('main.product_buy_failed'));;
        }
        else
        {
            return redirect($request->back_url);
        }

    }
    public function verify(Request $request){

        $user = auth()->user();
        $type = "charge";
        $transaction = TransactionCharge::where('user_id', $user->id)->where('authority', $request->Authority)->first();
        if (!$transaction){
            $transaction = Transaction::where('buyer_id', $user->id)->where('authority', $request->Authority)->first();
            $type = "payment";
        }

        $MerchantID = '68255607-b45b-4f9c-834a-2451d66c2ba0';
        $Authority =$request->get('Authority') ;

        $Amount = $transaction->price;
        if ($request->get('Status') == 'OK') {
//            $client = new nusoap_client('https://www.zarinpal.com/pg/services/WebGate/wsdl', 'wsdl');
            $client = new \nusoap_client('https://sandbox.zarinpal.com/pg/services/WebGate/wsdl', 'wsdl');
            $client->soap_defencoding = 'UTF-8';

            $result = $client->call('PaymentVerification', [
                [
                    'MerchantID'     => $MerchantID,
                    'Authority'      => $Authority,
                    'Amount'         => $Amount,
                ],
            ]);




            if ($result['Status'] == 100) {
                if($type == "charge"){
                    /** add payment ID to session **/
                    $transaction->mode = "deliver";
                    $transaction->save();

                    Balance::create([
                        'title' => trans('main.charge_account'),
                        'description' => trans('افزایش موجودی کیف پول'),
                        'type' => 'add',
                        'price' => $Amount,
                        'mode' => 'auto',
                        'user_id' => $user->id,
                        'exporter_id' => 0,
                        'created_at' => time()
                    ]);

                    $user->credit = $user->credit + $Amount;
                    $user->save();
                }


            } else {
                \Session::put('error', 'Unknown error occurred');
                // return 'NO';
            }
            return redirect('/user/balance/charge');
        }
        else
        {
            return redirect($request->back_url);
        }
    }
}
