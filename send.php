<?php
if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    header('HTTP/1.1 400 Bad Request');
    exit;
}

session_start();

/*
 * Error responses
{
    "multicast_id": 7.5633903902171e+18,
    "success": 0,
    "failure": 1,
    "canonical_ids": 0,
    "results": [
        {
            "error": "NotRegistered"
        }
    ]
}
{
    "multicast_id": 7.7873899680195e+18,
    "success": 0,
    "failure": 1,
    "canonical_ids": 0,
    "results": [
        {
            "error": "InvalidRegistration"
        }
    ]
}
*/

$url = 'https://fcm.googleapis.com/fcm/send';
$YOUR_API_KEY = 'AAAAaGQ_q2M:APA91bGCEOduj8HM6gP24w2LEnesqM2zkL_qx2PJUSBjjeGSdJhCrDoJf_WbT7wpQZrynHlESAoZ1VHX9Nro6W_tqpJ3Aw-A292SVe_4Ho7tJQCQxSezDCoJsnqXjoaouMYIwr34vZTs';

$request_body = [
    'to' => $_SESSION['token'],
    'data' => [
        'title' => 'Ералаш',
        'body' => sprintf('Начало в %s.', date('H:i')),
        'icon' => 'https://eralash.ru.rsz.io/sites/all/themes/eralash_v5/logo.png?width=40&height=40',
        'click_action' => 'http://eralash.ru/',
    ],
];
$request_headers = [
    'Content-Type: application/json',
    'Authorization: key=' . $YOUR_API_KEY,
];

$fields = json_encode($request_body);


$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
curl_setopt($ch, CURLOPT_HTTPHEADER, $request_headers);
curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
echo curl_exec($ch);
curl_close($ch);
