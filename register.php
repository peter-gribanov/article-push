<?php
if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    header('Location: /', 302);
    exit;
}

session_start();

$_SESSION['token'] = $_POST['token'];
