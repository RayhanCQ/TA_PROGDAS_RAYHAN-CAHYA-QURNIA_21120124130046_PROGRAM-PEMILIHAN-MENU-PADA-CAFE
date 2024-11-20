<?php
require_once 'Order.php';
session_start();

$menu_drinks = ["Iced Americano", "Hot Americano", "Iced Latte", "Hot Latte", "Espresso Single", "Espresso Double", "Lychee Tea", "Hot Tea", "Iced Tea"];
$menu_snacks = ["Tempe Mendoan", "French Fries", "Onion Rings", "Chicken Nugget"];

$orderid = $_GET['id'] ?? null;

if (isset($_SESSION['orders'][$orderid])) {
    $order = $_SESSION['orders'][$orderid];
} else { //pesan kalau error aja, harusnya gak mungkin ada
    echo "Order not found! Debug:";
    var_dump($_GET['id'], $_SESSION['orders']);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $orderid = $_GET['id'];
    $snacks = [];
    $drinks = [];

    foreach ($menu_snacks as $snack) {
        $qty = $_POST['snacks'][$snack] ?? 0;
        if ($qty > 0) {
            $snacks[$snack] = (int)$qty;
        }
    }

    foreach ($menu_drinks as $drink) {
        $qty = $_POST['drinks'][$drink] ?? 0;
        if ($qty > 0) {
            $drinks[$drink] = (int)$qty;
        }
    }
    //fungsi buat update variabel snack sama minumannya
    if (isset($_SESSION['orders'][$orderid])) {
        $order = $_SESSION['orders'][$orderid];
        $order->updateOrder($snacks, $drinks);
        $_SESSION['orders'][$orderid] = $order;
    }

    header("Location: order_confirm.php?id=" . $order->getId());
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ubah Pesanan</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h1>Ubah Pesanan Dengan ID #<?= $order->getId(); ?></h1>
        <form method="POST">
            <div class="mb-3">
                <label>Snack:</label>
                <?php foreach ($menu_snacks as $snack): ?>
                    <div>
                        <label><?= $snack; ?></label>
                        <input 
                            type="number" 
                            name="snacks[<?= $snack; ?>]" 
                            value="<?= $order->getSnacks()[$snack] ?? 0; ?>" 
                            min="0" 
                            max="10">
                    </div>
                <?php endforeach; ?>
            </div>
            <div class="mb-3">
                <label>Minuman:</label>
                <?php foreach ($menu_drinks as $drink): ?>
                    <div>
                        <label><?= $drink; ?></label>
                        <input 
                            type="number" 
                            name="drinks[<?= $drink; ?>]" 
                            value="<?= $order->getDrinks()[$drink] ?? 0; ?>" 
                            min="0" 
                            max="10">
                    </div>
                <?php endforeach; ?>
            </div>
            <button type="submit">Update Pesanan</button>
        </form>
    </div>
</body>
</html>
