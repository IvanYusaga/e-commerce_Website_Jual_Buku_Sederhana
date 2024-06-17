<?php
require_once __DIR__ . '/cek-akses.php';
checkUserAccess('home');

// Cek akses sebelum menampilkan tambah produk pesan sukses
if (isset($_GET['pesanProduk']) && $_GET['pesanProduk'] == 'sukses' && hasAccess('tambahProduk')) {
    echo "<script>
            alert('produk berhasil di tambahkan');
        </script>";
}

// Cek akses sebelum menampilkan hapus produk pesan sukses
if (isset($_GET['pesanHapus']) && $_GET['pesanHapus'] == 'sukses' && hasAccess('hapusProduk')) {
    echo "<script>
            alert('produk berhasil di hapus');
        </script>";
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
        <?php include_once 'list-produk.php'; ?>
    </div>
</body>

</html>