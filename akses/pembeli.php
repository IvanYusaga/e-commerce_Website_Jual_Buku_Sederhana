<?php
$access = require __DIR__ . '/tamu.php';
return array_merge($access, [
    'lihatPesanan' => true,
    'lihatDaftarPesanan' => true,
    'simpanKeranjangLogin' => true,
    'simpanKeranjangTamu' => false,
    'lihatDaftarPesananTamu' => false,
    'lihatDaftarPesananLogin' => true,
    'tambahProduk' => false,
    'buatPesanan' => false,
    'keranjang' => true,
    'lihatKeranjang' => true,
]);
