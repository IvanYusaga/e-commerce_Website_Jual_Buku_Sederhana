<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900&display=swap" rel="stylesheet" />
    <link href="styles.css" rel="stylesheet">
</head>

<body>
    <?php
    require_once __DIR__ . '/cek-akses.php';
    checkUserAccess('keranjang');
    if (!empty($_POST)) {
        foreach ($_POST['qty'] as $id => $jumlah) {
            $_SESSION['keranjang'][$id] = max($jumlah, 1);
        }

        // Hapus produk dari keranjang jika jumlahnya 0
        foreach ($_SESSION['keranjang'] as $id => $jumlah) {
            if ($jumlah == 0) {
                unset($_SESSION['keranjang'][$id]);
            }
        }

        // Jika keranjang kosong, hapus session keranjang
        if (empty($_SESSION['keranjang'])) {
            unset($_SESSION['keranjang']);
        }

        header('Location: keranjang.php');
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
        <title>Keranjang Belanja</title>
    </head>

    <body>
        <?php include 'menu.php'; ?>
        <div class="container-cart" style="width: 100%; height: 100%; display: flex; flex-direction: column; justify-content: center; align-items: center; padding: 25px;">
            <?php if (isset($_SESSION['keranjang']) && count($_SESSION['keranjang']) > 0) { ?>
                <form method="POST" action="">
                    <?php
                    $pdo = require_once 'koneksi.php';
                    $idProduk = array_keys($_SESSION['keranjang']);
                    if (count($idProduk) > 0) {
                        $placeholders = implode(',', array_fill(0, count($idProduk), '?'));
                        $sql = 'SELECT p.*, g.gambar FROM produk p
                                JOIN gambar_produk g ON p.id = g.id_produk
                                WHERE p.id IN (' . $placeholders . ') AND g.utama = 1';
                        $queery = $pdo->prepare($sql);
                        $queery->execute($idProduk);

                        $total = 0;
                        $jumlah = 0;
                        $i = 1;
                        while ($produk = $queery->fetch()) {
                            $total += $produk['harga'] * $_SESSION['keranjang'][$produk['id']];
                            $jumlah += $_SESSION['keranjang'][$produk['id']];
                    ?>
                            <div class="wrapper">
                                <div class="wrap-total" style="width: 900px; height: 70px; background-color: rgb(167, 167, 167); display: flex; justify-content: center; align-items: center; box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.2);">
                                    <div class="pTotal">Pesanan <?php echo $i;
                                                                $i++; ?></div>
                                </div>

                                <div class="wrap-cart" style="width: 900px; height: 140px; background-color: #fff; display: flex; justify-content: center; align-items: center; gap: 100px; padding: 10px; box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.2);">
                                    <div class="gambar"><img src="images/<?php echo $produk['gambar']; ?>" style="width: 100px; height: 100px;" alt="Gambar Produk"></div>
                                    <div class="card-cart" style="display: flex; flex-direction: column; gap: 20px;">
                                        <div class="nama"><?php echo htmlentities($produk['nama']); ?></div>
                                        <div class="text-end">Rp <?php echo number_format($produk['harga'], 0, ',', '.'); ?></div>
                                    </div>
                                    <div class="qty"><input type="number" name="qty[<?php echo $produk['id']; ?>]" value="<?php echo $_SESSION['keranjang'][$produk['id']]; ?>" class="form-control w-auto" /></div>
                                    <div class="card-cart" style="display: flex; flex-direction: column; gap: 20px;">
                                        <div class="text-end">Rp <?php echo number_format($produk['harga'] * $_SESSION['keranjang'][$produk['id']], 0, ',', '.'); ?></div>
                                        <div class="hapus-cart">
                                            <a href="hapus-keranjang.php?id=<?php echo $produk['id']; ?>" onclick="return confirm('Apakah Anda yakin menghapus produk ini dari keranjang belanja?');">Hapus</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="mt" style="margin-top: 20px;"></div>
                    <?php }
                    } ?>

                    <div class="wrap-btn" style="width: 900px; height: 70px; display: flex; justify-content: space-between; align-items: center; gap: 500px; padding: 10px;">
                        <div class="totaloverall">Total Rp <?php echo number_format($total, 0, ',', '.'); ?></div>
                        <div class="btn-cart">
                            <?php
                            if (hasAccess('editKeranjang')) {
                            ?>
                                <button type="submit" class="btn btn-secondary">Update</button>
                            <?php } ?>

                            <?php
                            if (hasAccess('simpanKeranjangLogin')) {
                            ?>
                                <a href="simpan-order-login.php" class="btn btn-primary">Pesan</a>
                            <?php } ?>

                            <?php
                            if (hasAccess('simpanKeranjangTamu')) {
                            ?>
                                <a href="simpan-order.php" class="btn btn-primary">Pesan</a>
                            <?php } ?>
                        </div>
                    </div>
                </form>
            <?php } else { ?>
                <div class="containerEmpty" style="width: 100%; height: 100%; display: flex; flex-direction: column; justify-content: center; align-items: center;">
                    <img src="images/272-2727925_continue-shopping-empty-cart-png-transparent-png-removebg-preview.png" alt="p">
                    <p style="font-size : 17px;">Keranjang Belanja Kosong</p>
                </div>
            <?php } ?>
        </div>
    </body>

    </html>
</body>

</html>
