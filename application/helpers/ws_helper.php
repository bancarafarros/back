<?php

$ci = &get_instance();

function ws_get($url){
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));

    $output = curl_exec($ch);
    $output = json_decode($output);

    curl_close($ch);

    return $output;
}

function auth_get($url){
    $ci =& get_instance();
    // persiapkan curl
    $ch = curl_init();

    // set url
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json','Authorization:'.$ci->session->userdata('token')));

    // return the transfer as a string
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

    // $output contains the output string
    $output = curl_exec($ch);
    $output = json_decode($output);

    // tutup curl
    curl_close($ch);

    // mengembalikan hasil curl
    return $output;
}

function ws_post($url, $data)
{
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    $result = curl_exec($ch);
    $result = json_decode($result);
    curl_close($ch);
    return $result;
}

function auth_post($url, $data)
{
    $ci =& get_instance();

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json','Authorization:'.$ci->session->userdata('token')));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    $result = curl_exec($ch);
    $result = json_decode($result);
    curl_close($ch);
    return $result;
}

function ws_post_upload($url, $data)
{
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json','Enctype:multipart/form-data'));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $result = curl_exec($ch);
    $result = json_decode($result);
    curl_close($ch);
    return $result;
}

function ws_put($url, $data)
{
    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'PUT');
    curl_setopt($curl, CURLOPT_HEADER, false);
    curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    $result     = curl_exec($curl);
    $result = json_decode($result);
    curl_close($curl);

    return json_decode($result);
}

function ws_delete($url)
{
    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'DELETE');
    curl_setopt($curl, CURLOPT_HEADER, false);
    curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    $result = curl_exec($curl);
    $result = json_decode($result);
    curl_close($curl);
    return $result;
}

function send_pin($params)
{
    $userkey = '889abc8a526c';
    $passkey = 'bb2b20ef51e03cd7a6686f0b';
    $message = '*Rosana Tour and Travel*
Untuk melanjutkan proses pendaftaran, silahkan masukkan PIN berikut pada halaman pendaftaran.
'.$params['pin_pendaftaran'].'
*Terima Kasih*';
    $url = 'https://console.zenziva.net/wareguler/api/sendWA/';
    $curlHandle = curl_init();
    curl_setopt($curlHandle, CURLOPT_URL, $url);
    curl_setopt($curlHandle, CURLOPT_HEADER, 0);
    curl_setopt($curlHandle, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curlHandle, CURLOPT_SSL_VERIFYHOST, 2);
    curl_setopt($curlHandle, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($curlHandle, CURLOPT_TIMEOUT,30);
    curl_setopt($curlHandle, CURLOPT_POST, 1);
    curl_setopt($curlHandle, CURLOPT_POSTFIELDS, array(
        'userkey' => $userkey,
        'passkey' => $passkey,
        'to' => $params['nomor_hp'],
        'message' => $message
    ));
    $results = json_decode(curl_exec($curlHandle), true);
    curl_close($curlHandle);
    return $results;
}


