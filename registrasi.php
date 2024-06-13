<?php
require_once __DIR__ . '/cek-akses.php';
$error = '';
if (!empty($_POST)) {
    try {
        if ($_POST['password'] != $_POST['password2']) {
            throw new Exception('Password dan ketik ulang password harus sama');
        }
        $pdo = require 'koneksi.php';
        $pdo->beginTransaction();
        $queryUser = $pdo->prepare('INSERT INTO users (nama, email, password, tipe)
        values(:nama, :email, :password, :tipe)');
        $queryUser->execute([
            'nama' => $_POST['nama'],
            'email' => $_POST['email'],
            'password' => sha1($_POST['password']),
            'tipe' => $_POST['tipe'],
        ]);
        $pdo->commit();
    } catch (PDOException $e) {
        $pdo->rollback();
        if ($e->errorInfo[0] == 23505) {
            $error = 'Email sudah tercatat, gunakan yang lain';
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
    <title>Studi Kasus Ecommerce</title>
</head>

<body>
    <?php
    include 'menu.php';
    ?>
    <div class="container">
        <?php
        if (!empty($error)) {
            echo '<p class="alert alert-danger">' . $error . '</p>';
        }
        ?>
        <form method="POST" action="">
            <div class="row">
                <div class="col-md-6">
                    <h4>Register</h4>
                    <hr />
                    <div class="mb-3">
                        <label class="form-label">Nama</label>
                        <input name="nama" type="text" class="form-control" value="<?php echo $_POST['nama'] ?? ''; ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input name="email" type="email" class="form-control" value="<?php echo $_POST['email'] ?? ''; ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input name="password" type="password" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Ketik Ulang Password</label>
                        <input name="password2" type="password" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Tipe User</label>
                        <select name="tipe" class="form-control" required>
                            <option value="pembeli">Pembeli</option>
                            <option value="admin">Admin</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="text-end">
                <button type="submit" class="btn btn-primary">Registrasi</button>
            </div>
        </form>
    </div>
</body>

</html>