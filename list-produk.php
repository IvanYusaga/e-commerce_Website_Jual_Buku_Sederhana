<?php
$pdo = require './koneksi.php';
$query = $pdo->prepare("SELECT * FROM produk");
$query->execute();
?>


<div class="container book-container">
    <?php
    while ($produk = $query->fetch()) {
    ?>
        <div class="book">
            <?php
            $queryGambar = $pdo->prepare("SELECT * FROM gambar_produk
                WHERE id_produk=:id AND utama=:utama");
            $queryGambar->execute([
                'id' => $produk['id'],
                'utama' => true,
            ]);
            $gambar = $queryGambar->fetch();
            if ($gambar) {
                echo '<a href="lihat-produk.php?id=' . $produk['id'] . '">
                        <img src="images/' . $gambar['gambar'] . '" class="rounded-top">
                        </a>';
            }
            ?>
            <div class="book-details">
                <h5 class="book-title"><a href="lihat-produk.php?id=<?php echo $produk['id']; ?>">
                        <?php echo htmlentities($produk['nama']); ?>
                    </a></h5>
                <p class="book-price">Rp <?php echo number_format($produk['harga'], 0, ',', '.'); ?></p>
            </div>
        </div>
    <?php } ?>
</div>