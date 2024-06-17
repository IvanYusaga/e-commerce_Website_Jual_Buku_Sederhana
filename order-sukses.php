<?php
require_once __DIR__ . '/cek-akses.php';
checkUserAccess('orderSukses');

if (!isset($_GET['id']) || empty($_GET['id'])) {
    header('Location: index.php');
    exit;
}

$pdo = require './koneksi.php';
$query = $pdo->prepare('SELECT * from pesanan where id=:id');
$query->execute(['id' => $_GET['id']]);
$pesanan = $query->fetch();
if (!$pesanan) {
    header('Location: index.php');
    exit;
}
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link href="styles.css" rel="stylesheet">
    <title>Studi Kasus Ecommerce</title>
</head>

<body>
    <?php
    include 'menu.php';
    ?>
    <div class="container">
        <div class="alert alert-success">Pesanan Tersimpan</div>
        <p>Terima kasih atas pesanan Anda. Kami akan segera proses pesanana Anda.
            ID Pesanan Anda Adalah: <a href="lihat-pesanan.php?id=<?php echo htmlentities($_GET['id']); ?>"><?php echo htmlentities($_GET['id']); ?></a>
        </p>
    </div>
    </div>
</body>

</html>