<?php
    // SAMPLE HIT API iPaymu v2 PHP //

    $va           = '1179000899'; //get on iPaymu dashboard
    $apiKey       = 'QbGcoO0Qds9sQFDmY0MWg1Tq.xtuh1'; //get on iPaymu dashboard

    $url          = 'https://sandbox.ipaymu.com/api/v2/payment'; // for development mode
    // $url          = 'https://my.ipaymu.com/api/v2/payment'; // for production mode
    
    $method       = 'POST'; //method
    
    //Request Body//
    $body['name']       = 'Nama Buyer';
    $body['phone']      = '08111511299';  //Pastikan data phone terdiri dari 5 sampai 15 digit angka
    $body['email']      = "buyer@mail.com"; 
    $body['amount']     = 'https://meeqostore.com//thank-you-page';
    $body['notifyUrl']  = 'https://meeqostore.com/cancel-page';
    $body['expired']    = 24;
    $body['paymentMethod'] = 'va'; //your reference id;
    $body['paymentChannel'] = 'bca';
    //End Request Body//

    //Generate Signature
    // *Don't change this
    $jsonBody     = json_encode($body, JSON_UNESCAPED_SLASHES);
    $requestBody  = strtolower(hash('sha256', $jsonBody));
    $stringToSign = strtoupper($method) . ':' . $va . ':' . $requestBody . ':' . $apiKey;
    $signature    = hash_hmac('sha256', $stringToSign, $apiKey);
    $timestamp    = Date('YmdHis');
    //End Generate Signature


    $ch = curl_init($url);

    $headers = array(
        'Accept: application/json',
        'Content-Type: application/json',
        'va: ' . $va,
        'signature: ' . $signature,
        'timestamp: ' . $timestamp
    );

    curl_setopt($ch, CURLOPT_HEADER, false);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    curl_setopt($ch, CURLOPT_POST, count($body));
    curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonBody);

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    $err = curl_error($ch);
    $ret = curl_exec($ch);
    curl_close($ch);

    if($err) {
        echo $err;
    } else {

        //Response
        $ret = json_decode($ret);
        if($ret->Status == 200) {
            $sessionId  = $ret->Data->SessionID;
            $url        =  $ret->Data->Url;
            header('Location:' . $url);
        } else {
            echo $ret;
        }
        //End Response
    }

?>
