<?php
$pdo = require './koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_produk = $_POST['id_produk'];

    // Ambil nama file gambar-gambar terkait dengan produk
    $queryAmbilGambar = $pdo->prepare("SELECT gambar FROM gambar_produk WHERE id_produk = :id_produk");
    $queryAmbilGambar->execute(['id_produk' => $id_produk]);
    $gambar_produk = $queryAmbilGambar->fetchAll(PDO::FETCH_ASSOC);

    // Menghapus gambar-gambar terkait
    foreach ($gambar_produk as $gambar) {
        $nama_file = $gambar['gambar'];
        $path_to_file = './images/' . $nama_file; // Sesuaikan dengan struktur folder Anda

        if (file_exists($path_to_file)) {
            unlink($path_to_file); // Hapus file dari sistem
        }
    }

    // Setelah menghapus gambar, hapus entri gambar dari tabel gambar_produk
    $queryHapusGambar = $pdo->prepare("DELETE FROM gambar_produk WHERE id_produk = :id_produk");
    $queryHapusGambar->execute(['id_produk' => $id_produk]);

    // Setelah itu baru hapus produk dari tabel produk
    $queryHapusProduk = $pdo->prepare("DELETE FROM produk WHERE id = :id_produk");
    $queryHapusProduk->execute(['id_produk' => $id_produk]);

    // Redirect ke halaman utama dengan pesan sukses atau error
    if ($queryHapusProduk->rowCount() > 0) {
        header('Location: index.php?pesanHapus=sukses');
    } else {
        header('Location: index.php?pesanHapus=error');
    }
    exit(); // Pastikan keluar dari script setelah melakukan redirect
}
