<?php
    // Konfigurasi database
    $host = "localhost";  
    $port = "5433";          
    $dbname = "db_uas"; 
    $dbUser = "postgres";    
    $dbPassword = "456287";  

    $message = "";

    // Proses form submission
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $nama = $_POST["nama"] ?? "";
        $password = $_POST["password"] ?? "";
        $nama_admin = $_POST["nama_admin"] ?? "";

        // Hash password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        try {
            // Koneksi ke database
            $conn = new PDO("pgsql:host=$host;port=$port;dbname=$dbname", $dbUser, $dbPassword);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Query untuk menyimpan data pengguna admin
            $sql = "INSERT INTO user_admin (nama, password, nama_admin, status_aktif) 
                    VALUES (:nama, :password, :nama_admin, TRUE)";
            $stmt = $conn->prepare($sql);
            $stmt->execute([
                ":nama" => $nama,
                ":password" => $hashedPassword,
                ":nama_admin" => $nama_admin
            ]);

            $message = "Registrasi admin berhasil!";

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
        <link rel="stylesheet" href="style.css">
        <title>Register Admin</title>
    </head>

    <body>
        <div class="login-box">
            <div class="login-header">
                <header>Register Admin</header>
            </div>
            <form method="POST" action="register.php">
                <div class="input-box">
                    <input type="text" name="nama" class="input-field" placeholder="Nama" autocomplete="off" required>
                </div>
                <div class="input-box">
                    <input type="password" name="password" class="input-field" placeholder="Password" autocomplete="off" required>
                </div>
                <div class="input-box">
                    <input type="text" name="nama_admin" class="input-field" placeholder="Nama Admin" autocomplete="off" required>
                </div>
                <div class="input-submit">
                    <button type="submit" class="submit-btn" id="submit"></button>
                    <label for="submit">Buat Akun Admin</label>
                </div>
            </form>

            <!-- Pesan hasil registrasi -->
            <?php if ($message): ?>
            <div class="login-message">
                <p><?php echo htmlspecialchars($message); ?></p>
            </div>
            <?php endif; ?>
        </div>
    </body>
</html>
