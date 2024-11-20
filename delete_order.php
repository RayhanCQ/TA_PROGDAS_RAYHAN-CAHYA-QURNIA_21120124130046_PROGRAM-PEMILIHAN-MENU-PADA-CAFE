<?php
require_once 'Order.php';
session_start();

$orderid = $_GET['id'] ?? null;

if ($orderid !== null) {
    $_SESSION['orders'] = array_filter($_SESSION['orders'], function ($order) use ($orderid) {
        return $order->getId() !== intval($orderid);
    });
}

header("Location: index.php");
exit();
?>