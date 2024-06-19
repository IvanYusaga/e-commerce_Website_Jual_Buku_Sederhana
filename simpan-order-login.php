<?php
require_once __DIR__ . '/cek-akses.php';
checkUserAccess('lihatDaftarPesananLogin');
$error = '';

if (!empty($_POST)) {
    try {
        // Ambil id_user dari sesi
        $idUser = $_SESSION['user']['id'];

        $pdo = require 'koneksi.php';
        $pdo->beginTransaction();

        // Masukkan alamat baru
        $queryAlamat = $pdo->prepare('INSERT INTO alamat
        (jalan, desa, kecamatan, kabupaten, provinsi, kodepos, id_user)
        values (:jalan, :desa, :kecamatan, :kabupaten, :provinsi, :kodepos, :id_user)');
        $queryAlamat->execute([
            'jalan' => $_POST['jalan'],
            'desa' => $_POST['desa'],
            'kecamatan' => $_POST['kecamatan'],
            'kabupaten' => $_POST['kabupaten'],
            'provinsi' => $_POST['provinsi'],
            'kodepos' => $_POST['kodepos'],
            'id_user' => $idUser,
        ]);
        $idAlamat = $pdo->lastInsertId();

        // Masukkan pesanan baru
        $queryPesanan = $pdo->prepare('INSERT INTO pesanan
        (id_user, total_harga, tanggal_pesanan, id_alamat)
        values (:id_user, :total_harga, now(), :id_alamat)');
        $queryPesanan->execute([
            'id_user' => $idUser,
            'total_harga' => 0,
            'id_alamat' => $idAlamat,
        ]);
        $idPesanan = $pdo->lastInsertId();

        $total = 0;
        foreach ($_SESSION['keranjang'] as $idProduk => $qty) {
            $queryProduk = $pdo->prepare('SELECT * FROM produk WHERE id=:id');
            $queryProduk->execute(['id' => $idProduk]);
            $produk = $queryProduk->fetch();
            $queryItem = $pdo->prepare('INSERT INTO item_pesanan
            (id_pesanan, id_produk, harga, qty)
            values (:id_pesanan, :id_produk, :harga, :qty)');
            $queryItem->execute([
                'id_pesanan' => $idPesanan,
                'id_produk' => $idProduk,
                'harga' => $produk['harga'],
                'qty' => $qty,
            ]);
            $total += ($produk['harga'] * $qty);
        }

        $queryUpdate = $pdo->prepare('UPDATE pesanan SET total_harga=:total WHERE id=:id');
        $queryUpdate->execute([
            'id' => $idPesanan,
            'total' => $total,
        ]);
        $pdo->commit();

        // Hapus keranjang belanja
        unset($_SESSION['keranjang']);
        header('Location: order-sukses.php?id=' . $idPesanan);
        exit;
    } catch (PDOException $e) {
        $pdo->rollback();
        if ($e->errorInfo[0] == 23505) {
            $error = 'Terjadi kesalahan saat menyimpan data';
        } else {
            $error = 'Terjadi kesalahan saat menyimpan data';
        }
        error_log($e->getMessage());
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link href="styles.css" rel="stylesheet">

    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900&display=swap" rel="stylesheet" />
    <title>Studi Kasus Ecommerce</title>
</head>

<body>
    <?php include 'menu.php'; ?>
    <div class="container-order" style="width: 100%; height: 100vh; display: flex; justify-content: center; align-items: center;">
        <div class="wrapper-order" style="width: 100%; max-width: 900px; padding: 20px; background-color: rgb(238, 238, 238); display: flex; flex-direction: column; justify-content: center; align-items: center; box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.2);">
            <?php if (!empty($error)) { echo '<p class="alert alert-danger">' . $error . '</p>'; } ?>
            <form method="POST" action="" style="width: 100%;">
                <div class="row">
                    <div class="col-md-12">
                        <h4>Alamat Pengiriman</h4>
                        <hr />
                        <div class="mb-3">
                            <label class="form-label">Jalan</label>
                            <input name="jalan" type="text" class="form-control" value="<?php echo $_POST['jalan'] ?? ''; ?>" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Desa / kelurahan</label>
                            <input name="desa" type="text" class="form-control" value="<?php echo $_POST['desa'] ?? ''; ?>">
                        </div>
                        <div class="row">
                            <div class="mb-3 col-md-6">
                                <label class="form-label">Kecamatan</label>
                                <input name="kecamatan" type="text" value="<?php echo $_POST['kecamatan'] ?? ''; ?>" class="form-control">
                            </div>
                            <div class="mb-3 col-md-6">
                                <label class="form-label">Kabupaten</label>
                                <input name="kabupaten" type="text" value="<?php echo $_POST['kabupaten'] ?? ''; ?>" class="form-control">
                            </div>
                            <div class="mb-3 col-md-6">
                                <label class="form-label">Provinsi</label>
                                <input name="provinsi" value="<?php echo $_POST['provinsi'] ?? ''; ?>" type="text" class="form-control">
                            </div>
                            <div class="mb-3 col-md-6">
                                <label class="form-label">Kode Pos</label>
                                <input name="kodepos" value="<?php echo $_POST['kodepos'] ?? ''; ?>" type="text" class="form-control">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="text-end">
                    <button type="submit" class="btn btn-primary">Pesan</button>
                </div>
            </form>
        </div>
    </div>
</body>

</html>
