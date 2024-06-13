<?php
require_once __DIR__ . '/cek-akses.php';
?>

<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid">
        <a class="navbar-brand" href="index.php">Navbar</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="index.php">Home</a>
                </li>
                <?php if (hasAccess('lihatKeranjang')) { ?>
                    <li class="nav-item">
                        <a class="nav-link" href="keranjang.php">Keranjang</a>
                    </li>
                <?php } ?>
                <?php
                if (hasLogin()) {
                ?>
                    <?php if (hasAccess('lihatDaftarPesanan')) { ?>
                        <li class="nav-item">
                            <a class="nav-link" href="pesanan.php">Pesanan</a>
                        </li>
                    <?php } ?>
                    <?php if (hasAccess('buatPesanan')) { ?>
                        <li class="nav-item">
                            <a class="nav-link" href="tambah-produk.php">Unggah Produk</a>
                        </li>
                    <?php } ?>
                    <li class="nav-item">
                        <a class="nav-link" href="logout.php">Logout</a>
                    </li>
                <?php } else { ?>
                    <li class="nav-item">
                        <a class="nav-link" href="login.php">Login</a>
                    </li>
                <?php } ?>
            </ul>
        </div>
    </div>
</nav>