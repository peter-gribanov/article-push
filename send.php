<?php
if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    header('Location: /', 302);
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
$YOUR_API_KEY = 'AIzaSyCpwY3CCP-snMnfaktCecEp_x5zLFDLmDk';

$request_body = [
    'to' => $_SESSION['token'],
    'notification' => [
        'title' => 'Ералаш',
        'body' => sprintf('Начало в %s.', date('H:i')),
        'icon' => 'https://eralash.ru.rsz.io/sites/all/themes/eralash_v5/logo.png?width=40&height=40',
        'click_action' => 'http://eralash.ru/',
    ],
];
$fields = json_encode($request_body, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);

$request_headers = [
    'Content-Type: application/json',
    'Authorization: key=' . $YOUR_API_KEY,
    'Content-Length: ' . strlen($fields),
];

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
curl_setopt($ch, CURLOPT_HTTPHEADER, $request_headers);
curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
//curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
//curl_setopt($ch, CURLOPT_COOKIESESSION, true);
curl_setopt($ch, CURLOPT_FORBID_REUSE, true);
curl_setopt($ch, CURLOPT_FRESH_CONNECT, true);
//curl_setopt($ch, CURLOPT_DNS_CACHE_TIMEOUT, 0);
//curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
curl_setopt($ch, CURLOPT_HEADER, true);

$response = curl_exec($ch);
$response_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$response_headers_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
$response_headers = substr($response, 0, $response_headers_size);
$response_body = substr($response, $response_headers_size);
curl_close($ch);

if ($response_code == 200 && ($data = @json_decode($response_body))) {
    $response_body = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>Test push notification</title>
</head>
<body>
<section>
    <header>
        <h3>Request</h3>
    </header>
    <article>
        <strong>Headers:</strong>
        <pre><code><?=implode(PHP_EOL, $request_headers)?></code></pre>
        <strong>Body:</strong>
        <pre><code><?=json_encode($request_body, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE)?></code></pre>
    </article>
</section>
<br>
<section>
    <header>
        <h3>Response (<?=$response_code?>)</h3>
    </header>
    <article>
        <strong>Headers:</strong>
        <pre><code><?=$response_headers?></code></pre>
        <strong>Body:</strong>
        <pre><code><?=$response_body?></code></pre>
    </article>
</section>
<br>
<a href="/index.html">< Go back</a>
</body>
</html>
