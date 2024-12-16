<?php 
    $host = "localhost";  
    $port = "5433";         
    $dbname = "db_uas"; 
    $dbUser = "postgres";   
    $dbPassword = "456287";   

    $message = ""; 
    session_start();
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $nama = $_POST["nama"] ?? "";
        $password = $_POST["password"] ?? "";

        try {
            $conn = new PDO("pgsql:host=$host;port=$port;dbname=$dbname", $dbUser, $dbPassword);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Query untuk mencari data admin berdasarkan nama
            $sql = "SELECT * FROM user_admin WHERE nama = :nama";
            $stmt = $conn->prepare($sql);
            $stmt->execute([":nama" => $nama]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            // Verifikasi password
            if ($user && password_verify($password, $user["password"])) {
                $_SESSION["nama_admin"] = $user["nama"];
                $message = "Login berhasil! Selamat datang, " . htmlspecialchars($user["nama_admin"]) . "!";
                header("Location: admin.php");
                exit;
            } else {
                $message = "Nama atau password salah.";
            }
        } catch (PDOException $e) {
            $message = "Error: " . $e->getMessage();
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <div class="row justify-content-center mt-5">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h3 class="text-center">Login</h3>
                    </div>
                    <div class="card-body">
                        <form action="" method="POST">
                            <div class="mb-3">
                                <label for="nama" class="form-label">Nama</label>
                                <input type="text" name="nama" class="form-control" id="nama" placeholder="Nama" required>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" name="password" class="form-control" id="password" placeholder="Password" required>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Masuk</button>
                        </form>
                        <?php if ($message): ?>
                            <div class="alert alert-danger mt-3">
                                <p><?php echo htmlspecialchars($message); ?></p>
                            </div>
                        <?php endif; ?>
                        <div class="text-center mt-3">
                            <p>Tidak punya akun? <a href="register.php">Daftar</a></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
