<?php
require_once 'Order.php';
session_start();

if (!isset($_SESSION['orders'])) {
    $_SESSION['orders'] = [];
}

if (!isset($_SESSION['order_counter'])) {
    $_SESSION['order_counter'] = 1;
}

$menu_drinks = ["Iced Americano", "Hot Americano", "Iced Latte", "Hot Latte", "Espresso Single", "Espresso Double", "Lychee Tea", "Hot Tea", "Iced Tea"];
$menu_snacks = ["Tempe Mendoan", "French Fries", "Onion Rings", "Chicken Nugget"];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'] ?? '';
    $snacks = [];
    $drinks = [];

    foreach ($_POST['snacks'] as $snack => $quantity) {
        if ($quantity > 0) {
            $snacks[$snack] = (int)$quantity;
        }
    }
    foreach ($_POST['drinks'] as $drink => $quantity) {
        if ($quantity > 0) {
            $drinks[$drink] = (int)$quantity;
        }
    }

    $orderid = $_SESSION['order_counter'];
    $newOrder = new Order($orderid, $name, $snacks, $drinks);
    $_SESSION['orders'][$orderid] = $newOrder;
    $_SESSION['order_counter']++;
    header("Location: order_confirm.php?id=$orderid");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Website CQCafe</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h1>Selamat Datang di CQCafe!!!</h1>
        <form method="POST">
            <div class="mb-3">
                <label for="name">Siapa Namamu?:</label>
                <input type="text" id="name" name="name" required>
            </div>
            <div class="mb-3">
                <label>Silahkan Pilih Minuman!:</label>
                <?php foreach ($menu_drinks as $drink): ?>
                    <div class="mb-2">
                        <label><?= $drink ?></label>
                        <input type="number" name="drinks[<?= $drink ?>]" min="0" max="10" value="0">
                    </div>
                <?php endforeach; ?>
            </div>
            <div class="mb-3">
                <label>Silahkan Pilih Snack!:</label>
                <?php foreach ($menu_snacks as $snack): ?>
                    <div class="mb-2">
                        <label><?= $snack ?></label>
                        <input type="number" name="snacks[<?= $snack ?>]" min="0" max="10" value="0">
                    </div>
                <?php endforeach; ?>
            </div>
            <button type="submit">Konfirmasi Pesanan</button>
        </form>
    </div>
</body>
</html>
