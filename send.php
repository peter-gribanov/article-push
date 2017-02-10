<?php
session_start();

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

$request_headers = [
    'Content-Type: application/json',
    'Authorization: key=' . $YOUR_API_KEY,
];

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
curl_setopt($ch, CURLOPT_HTTPHEADER, $request_headers);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($request_body, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
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
<h3>Request</h3>
headers:
<pre><code><?=implode(PHP_EOL, $request_headers)?></pre>
body:
<pre><code><?=json_encode($request_body, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE)?></pre>
<br>
<h3>Response (<?=$response_code?>)</h3>
headers:
<pre><code><?=$response_headers?></pre>
body:
<pre><code><?=$response_body?></pre>
<br>
<a href="/index.html">< Go back</a>
</body>
</html>
<script src="https://www.gstatic.com/firebasejs/3.6.8/firebase.js"></script>
<script src="/app.js"></script>
