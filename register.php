<?php
if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    header('HTTP/1.1 400 Bad Request');
    exit;
}

session_start();

$_SESSION['token'] = $_POST['token'];
