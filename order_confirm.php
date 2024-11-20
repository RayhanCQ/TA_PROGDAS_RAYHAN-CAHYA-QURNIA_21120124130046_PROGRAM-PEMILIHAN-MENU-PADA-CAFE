<?php
require_once 'Order.php';
session_start();

$orderid = $_GET['id'] ?? null;
$order = null;

foreach ($_SESSION['orders'] as $o) {
    if ($o->getId() === intval($orderid)) {
        $order = $o;
        break;
    }
}

//ini buat debug aja, memastikan ID dan order ada di session
if (isset($_GET['id'])) {
    $orderid = $_GET['id'];

    if (isset($_SESSION['orders'][$orderid])) {
        $order = $_SESSION['orders'][$orderid];
    } else {
        echo "Order not found!";
        exit();
    }
} else {
    echo "No order ID provided!";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action'])) {
    if ($_POST['action'] == 'update') {
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
        $order->updateOrder($snacks, $drinks);
    } elseif ($_POST['action'] == 'delete') {
        $_SESSION['orders'] = array_filter($_SESSION['orders'], function ($o) use ($order) {
            return $o->getId() !== $order->getId();
        });
        header("Location: index.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Konfirmasi Pesanan</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container mt-5">
        <h1>Terima Kasih Atas Pesanannya!</h1>
        <p><strong>Nama:</strong> <?= $order->getName() ?></p>
        <p><strong>ID Pesanan:</strong> <?= $order->getId() ?></p>

        <h3>Detail Pesanan:</h3>
        <ul>
            <li><strong>Snack:</strong><?php foreach ($order->getSnacks() as $snack => $qty): ?>
                            <?= "$snack ($qty). "; ?>
                            <?php endforeach; ?></li>
            <li><strong>Minum:</strong><?php foreach ($order->getDrinks() as $drink => $qty): ?>
                            <?= "$drink ($qty). "; ?>
                            <?php endforeach; ?></li>
        </ul>

        <h3>Semua Order:</h3>
        <table class="table">
            <thead>
                <tr>
                    <th>ID Pesanan</th>
                    <th>Nama</th>
                    <th>Snack</th>
                    <th>Minum</th>
                    <th>Ubah/Hapus</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($_SESSION['orders'] as $o): ?>
                    <tr>
                        <td><?= $o->getId() ?></td>
                        <td><?= $o->getName() ?></td>
                        <td><?php foreach ($o->getSnacks() as $snack => $qty): ?>
                            <?= "$snack ($qty)"; ?><br>
                            <?php endforeach; ?>
                        </td>
                        <td><?php foreach ($o->getDrinks() as $drink => $qty): ?>
                            <?= "$drink ($qty)"; ?><br>
                            <?php endforeach; ?>
                        </td>
                        <td>
                            <a href="edit_order.php?id=<?=$order->getId();?>">Ubah</a>
                            <a href="delete_order.php?id=<?=$order->getId();?>" onclick="return confirm('Yakin Ingin Menghapus Pesanan?');">Hapus</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <a href='index.php' class='return-link'>Kembali Ke Halaman Awal</a>
    </div>
</body>
</html>
