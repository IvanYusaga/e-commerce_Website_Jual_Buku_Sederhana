<?php
$pdo = require './koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_produk = $_POST['id_produk'];

    // Menghapus gambar yang terkait dengan produk terlebih dahulu
    $queryHapusGambar = $pdo->prepare("DELETE FROM gambar_produk WHERE id_produk = :id_produk");
    $queryHapusGambar->execute(['id_produk' => $id_produk]);

    // Menghapus produk
    $queryHapusProduk = $pdo->prepare("DELETE FROM produk WHERE id = :id_produk");
    $queryHapusProduk->execute(['id_produk' => $id_produk]);

    if ($queryHapusProduk->rowCount() > 0) {
        // Redirect ke halaman utama dengan pesan sukses
        header('Location: index.php?pesan=sukses');
    } else {
        // Redirect ke halaman utama dengan pesan error
        header('Location: index.php?pesan=error');
    }
}
