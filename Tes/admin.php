<?php 
    session_start();
    
    // Mengecek apakah admin sudah login
    if (!isset($_SESSION['nama_admin'])) {
        header("Location: login.php");
        exit;
    }

    // Koneksi database
    $host = "localhost";  
    $port = "5433";         
    $dbname = "db_uas"; 
    $dbUser = "postgres";   
    $dbPassword = "456287";   

    try {
        $conn = new PDO("pgsql:host=$host;port=$port;dbname=$dbname", $dbUser, $dbPassword);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        die("Connection failed: " . $e->getMessage());
    }

    // Ambil nama admin
    $nama_admin = $_SESSION['nama_admin'];

    // Logout jika admin memilih untuk keluar
    if (isset($_POST['logout'])) {
        session_destroy();
        header("Location: login.php");
        exit;
    }

    // Proses untuk menambah resi
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['tambah_resi'])) {
        $nomor_resi = $_POST['nomor_resi'];
        $tanggal_resi = $_POST['tanggal_resi'];

        $stmt = $conn->prepare("INSERT INTO resi (nomor_resi, tanggal_resi) VALUES (:nomor_resi, :tanggal_resi)");
        $stmt->execute([':nomor_resi' => $nomor_resi, ':tanggal_resi' => $tanggal_resi]);
    }

    // Proses untuk menghapus resi
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['hapus_resi'])) {
        $id_resi = $_POST['id_resi'];

        $stmt = $conn->prepare("DELETE FROM resi WHERE id = :id_resi");
        $stmt->execute([':id_resi' => $id_resi]);
    }

    // Proses untuk menambahkan log pengiriman
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['tambah_log'])) {
        $id_resi = $_POST['id_resi'];
        $status = $_POST['status'];

        $stmt = $conn->prepare("INSERT INTO log_pengiriman (id_resi, status) VALUES (:id_resi, :status)");
        $stmt->execute([':id_resi' => $id_resi, ':status' => $status]);
    }

    // Proses untuk menambah user admin
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['tambah_admin'])) {
        $nama = $_POST['nama'];
        $nama_admin = $_POST['nama_admin'];
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

        $stmt = $conn->prepare("INSERT INTO user_admin (nama, nama_admin, password) VALUES (:nama, :nama_admin, :password)");
        $stmt->execute([':nama' => $nama, ':nama_admin' => $nama_admin, ':password' => $password]);
    }

    // Proses untuk menonaktifkan user admin
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['nonaktifkan_admin'])) {
        $id_admin = $_POST['id_admin'];

        $stmt = $conn->prepare("UPDATE user_admin SET status_aktif = FALSE WHERE id = :id_admin");
        $stmt->execute([':id_admin' => $id_admin]);
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Navbar -->
            <nav class="navbar navbar-expand-lg navbar-light bg-light w-100">
                <a class="navbar-brand" href="#">Selamat datang, <?php echo htmlspecialchars($nama_admin); ?></a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item">
                            <form method="POST">
                                <button type="submit" name="logout" class="btn btn-danger">Logout</button>
                            </form>
                        </li>
                    </ul>
                </div>
            </nav>

            <!-- Konten Utama -->
            <main class="col-md-9 ml-sm-auto col-lg-10 px-4 mt-4">
                <h2>Dashboard Admin</h2>

                <!-- Form untuk menambah resi -->
                <h3>Tambah Nomor Resi</h3>
                <form method="POST">
                    <div class="mb-3">
                        <label for="nomor_resi" class="form-label">Nomor Resi</label>
                        <input type="text" class="form-control" name="nomor_resi" id="nomor_resi" required>
                    </div>
                    <div class="mb-3">
                        <label for="tanggal_resi" class="form-label">Tanggal Resi</label>
                        <input type="date" class="form-control" name="tanggal_resi" id="tanggal_resi" required>
                    </div>
                    <button type="submit" name="tambah_resi" class="btn btn-primary">Tambah Resi</button>
                </form>

                <!-- List Resi -->
                <h3 class="mt-4">List Resi</h3>
                <?php
                    $stmt = $conn->query("SELECT * FROM resi");
                    while ($resi = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        echo "<div class='card mt-3'>
                                <div class='card-body'>
                                    <h5 class='card-title'>Nomor Resi: " . htmlspecialchars($resi['nomor_resi']) . "</h5>
                                    <p>Tanggal Resi: " . htmlspecialchars($resi['tanggal_resi']) . "</p>
                                    <form method='POST'>
                                        <input type='hidden' name='id_resi' value='" . $resi['id'] . "'>
                                        <button type='submit' name='hapus_resi' class='btn btn-danger'>Hapus Resi</button>
                                    </form>
                                </div>
                            </div>";
                    }
                ?>

                <!-- Pengaturan Log Pengiriman -->
                <h3 class="mt-4">Entry Log Pengiriman</h3>
                <form method="POST">
                    <div class="mb-3">
                        <label for="id_resi_log" class="form-label">Pilih Nomor Resi</label>
                        <select class="form-select" name="id_resi" id="id_resi_log" required>
                            <option value="">Pilih Resi</option>
                            <?php
                                // Menampilkan daftar nomor resi yang ada di database
                                $stmt = $conn->query("SELECT * FROM resi");
                                while ($resi = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                    echo "<option value='" . $resi['id'] . "'>" . htmlspecialchars($resi['nomor_resi']) . "</option>";
                                }
                            ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="status" class="form-label">Status Pengiriman</label>
                        <input type="text" class="form-control" name="status" id="status" required>
                    </div>
                    <button type="submit" name="tambah_log" class="btn btn-primary">Tambah Log Pengiriman</button>
                </form>

                <!-- Pengaturan User Akses -->
                <h3 class="mt-4">Pengaturan User Admin</h3>
                <form method="POST">
                    <div class="mb-3">
                        <label for="nama" class="form-label">Nama Lengkap</label>
                        <input type="text" class="form-control" name="nama" id="nama" required>
                    </div>
                    <div class="mb-3">
                        <label for="nama_admin" class="form-label">Nama Admin</label>
                        <input type="text" class="form-control" name="nama_admin" id="nama_admin" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" name="password" id="password" required>
                    </div>
                    <button type="submit" name="tambah_admin" class="btn btn-success">Tambah Admin</button>
                </form>

                <form method="POST" class="mt-3">
                    <div class="mb-3">
                        <label for="id_admin" class="form-label">ID Admin untuk Nonaktifkan</label>
                        <input type="number" class="form-control" name="id_admin" id="id_admin" required>
                    </div>
                    <button type="submit" name="nonaktifkan_admin" class="btn btn-danger">Nonaktifkan Admin</button>
                </form>
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
