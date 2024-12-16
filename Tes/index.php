<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Log Pengiriman</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <div class="container">
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <a class="navbar-brand" href="#">Dashboard</a>
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link" href="login.php">Login Admin</a>
                </li>
            </ul>
        </nav>

        <h2 class="mt-4">Cari Log Pengiriman Berdasarkan Nomor Resi</h2>

        <div class="mb-3">
            <label for="nomor_resi" class="form-label">Nomor Resi</label>
            <input type="text" class="form-control" id="nomor_resi" placeholder="Masukkan Nomor Resi" />
        </div>
        <button class="btn btn-primary" id="cari_log">Cari Log</button>

        <!-- Tabel untuk menampilkan log pengiriman -->
        <h3 class="mt-4">Log Pengiriman</h3>
        <table class="table table-striped mt-3" id="log_table">
            <thead>
                <tr>
                    <th>ID Log</th>
                    <th>Status Pengiriman</th>
                    <th>Tanggal Log</th>
                </tr>
            </thead>
            <tbody>
                <!-- Data log akan ditampilkan di sini -->
            </tbody>
        </table>
    </div>

    <script>
        $(document).ready(function () {
            $('#cari_log').click(function () {
                var nomorResi = $('#nomor_resi').val();
                if (nomorResi) {
                    // Mengirimkan request AJAX
                    $.ajax({
                        url: 'log_pengiriman.php',
                        method: 'GET',
                        data: { nomor_resi: nomorResi },
                        success: function(response) {
                            var logs = JSON.parse(response);
                            var tableBody = $('#log_table tbody');
                            tableBody.empty(); // Menghapus isi tabel sebelumnya

                            if (logs.length > 0) {
                                logs.forEach(function(log) {
                                    tableBody.append(`
                                        <tr>
                                            <td>${log.id}</td>
                                            <td>${log.status}</td>
                                            <td>${log.tanggal_log}</td>
                                        </tr>
                                    `);
                                });
                            } else {
                                tableBody.append('<tr><td colspan="3">Log tidak ditemukan</td></tr>');
                            }
                        }
                    });
                } else {
                    alert('Harap masukkan nomor resi.');
                }
            });
        });
    </script>
</body>
</html>
