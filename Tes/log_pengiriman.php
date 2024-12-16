<?php
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

// Cek apakah ada parameter nomor_resi yang dikirimkan
if (isset($_GET['nomor_resi'])) {
    $nomor_resi = $_GET['nomor_resi'];

    // Query untuk mencari log pengiriman berdasarkan nomor resi
    $stmt = $conn->prepare("SELECT * FROM log_pengiriman WHERE id_resi IN (SELECT id FROM resi WHERE nomor_resi = :nomor_resi)");
    $stmt->execute([':nomor_resi' => $nomor_resi]);

    // Mengambil hasil query
    $logs = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Mengirimkan hasil sebagai JSON
    echo json_encode($logs);
}
?>
