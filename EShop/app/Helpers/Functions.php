<?php


use App\Logger;
use App\User;
use App\OrgMember;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Support\Str;

function isJson($string)
{
    try {
        json_decode($string);
        return (json_last_error() == JSON_ERROR_NONE);
    } catch (Exception $e) {
        return false;
    }
}

function generateUniqueId($model, $columnCheckUnique)
{
    $username = Str::random(6); // better than rand()

    // call the same function if the barcode exists already
    if (usernameExists($username, $model, $columnCheckUnique)) {
        return generateUniqueId($model, $columnCheckUnique);
    }

    // otherwise, it's valid and can be used
    return $username;
}

function usernameExists($username, $model, $columnCheckUnique)
{
    // query the database and return a boolean
    // for instance, it might look like this in Laravel
    return $model->where($columnCheckUnique, $username)->exists();
}

function thumbImage($src)
{
    return str_replace('_original', '_thumb', $src);
}

function mediumImage($src)
{
    return str_replace('_original', '_medium', $src);
}

function smallImage($src)
{
    return str_replace('_original', '_small', $src);
}

function largeImage($src)
{
    return str_replace('_original', '_large', $src);
}


function generateBarcodeNumber()
{
    $number = mt_rand(1000000, 9999999); // better than rand()

    // call the same function if the barcode exists already
    if (barcodeNumberExists($number)) {
        return generateBarcodeNumber();
    }

    // otherwise, it's valid and can be used
    return $number;
}

function barcodeNumberExists($number)
{
    // query the database and return a boolean
    // for instance, it might look like this in Laravel
    return User::whereChatId($number)->exists();
}


function pushNotification($users, $title, $body, $clickActionUrl, $icon)
{


    $ids = $users->pluck('id');
    $fcmTokens = Logger::whereIn('user_id', $ids)->where('type', 'LIKE', '%fcm_token%')->get();


    if ($fcmTokens->count() === 0) {
        return;
    }


    foreach ($fcmTokens as $token) {
        $client = new Client();

        $response = $client->post(
            'https://fcm.googleapis.com/fcm/send',
            [
                'json' => [
                    'notification' => [
                        "title" => $title,
                        "body" => $body,
                        "click_action" => $clickActionUrl,
                        "icon" => $icon
                    ],
                    'to' => $token->value
                ],
                'headers' => [
                    'Authorization' => 'key=AAAABtv4LRo:APA91bF4alLnzNhl9cBXCwRRhTw_rXiNWut1TiVBvfUl0xq7jgVj2-Zi3PCpiM2y48_1srG2_HRXUZbAvptHOrBq5xhodM7t6plxQ_jZfUU8dmNaz4JPseg-YrOpDXtvQRTJy2nD1Qwo',
                    'Content-Type' => 'application/json'

                ]
            ]
        );
    }
}

function convertUrlToCacheKey($req)
{
    $url = (array)\parse_url($req->fullUrl());
    $query = $url['query'] ?? '';
    if ($query) {
        $key = $url['path'] . "?" . $query;
    } else {
        $key = $url['path'];
    }
    return $key;
}

