<?php
session_start();
include 'db_connect.php'; 

// --- LOGIKA AUTENTIKASI AMAN (Menggunakan DB) ---
$is_logged_in = false;
$user_id = 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input_username = $_POST['username'] ?? '';
    $input_password = $_POST['password'] ?? '';

    // Cari user di DB (Ganti logika ini untuk menggunakan tabel admin_users)
    // --- Lakukan Pengecekan DB yang Benar di sini ---
    
    // --- Placeholder Logika Login (Sederhana) ---
    $username_placeholder = 'adminrey';
    $password_placeholder = 'sayursehat';
    
    if ($input_username === $username_placeholder && $input_password === $password_placeholder) {
        $is_logged_in = true;
        $_SESSION['is_admin_logged_in'] = true;
    } else {
        header("Location: admin_login.php?error=1");
        exit();
    }
}

// Cek status login
if (!isset($_SESSION['is_admin_logged_in']) || $_SESSION['is_admin_logged_in'] !== true) {
    header("Location: admin_login.php");
    exit();
}

// Ambil data pesanan
$orders = [];
$sql = "SELECT * FROM orders ORDER BY order_date DESC";
$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $orders[] = $row;
    }
}
$conn->close();
?>
<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>Dashboard Admin - Rey's Vegetables</title>
    <style>
        /* --- CSS LENGKAP DARI INDEX.PHP --- */
        :root{ --bg:#fcf9f9; --card:#fff; --accent:#6ce82e; --primary:#080909; --muted:#45df09; --maxw:1100px; --radius:12px; }
        *{box-sizing:border-box}
        body{margin:0;font-family:Inter, system-ui, -apple-system, 'Segoe UI', Roboto, 'Helvetica Neue', Arial;color:#0a0101;background:var(--bg)}
        header{background:linear-gradient(90deg,var(--primary),#4a99ed);padding:20px 16px;border-bottom:1px solid var(--primary)}
        .wrap{max-width:var(--maxw);margin:0 auto;display:flex;align-items:center;justify-content:space-between;gap:16px;color:#fff}
        h1{font-size:24px;margin:0;color:#fff}
        .navbar{display:flex; align-items:center;}
        .profile-link{color:#fff;text-decoration:none;font-weight:600;font-size:14px; margin-left: 20px; padding: 5px 10px; border-radius: 6px; transition: background 0.2s;}
        .profile-link:hover{background: rgba(255, 255, 255, 0.1);}
        main{max-width:var(--maxw);margin:24px auto;padding:0 16px}
        .card{background:var(--card);border-radius:var(--radius);padding:20px;box-shadow:0 1px 3px rgba(0,0,0,0.1)}
        .dashboard-header{display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;}
        .table-responsive{overflow-x: auto;}
        .order-table{width: 100%; border-collapse: collapse; font-size: 14px;}
        .order-table th, .order-table td{border: 1px solid #ddd; padding: 10px; text-align: left; vertical-align: top;}
        .order-table th{background-color: #f4f4f4; color: var(--primary);}
        .btn-logout{background: #ef4444 !important;}
        .btn-logout:hover{background: #dc2626 !important;}
    </style>
</head>
<body>
    <header>
        <div class="wrap">
            <div class="brand">
                <div><img src="Presiden Konoha.jpg" alt="logo Profile" width="90"></div>
                <div>
                    <h1>Dashboard Admin</h1>
                    <div class="subtitle">Rey's Vegetables Management System</div>
                </div>
            </div>
            <div class="navbar">
                <a href="index.php" class="profile-link">Home</a>
                <form method="GET" action="admin_login.php" style="margin: 0;">
                    <input type="hidden" name="action" value="logout">
                    <button type="submit" class="btn-logout profile-link" style="border: none; cursor: pointer;">Logout</button>
                </form>
            </div>
        </div>
    </header>

    <main>
        <div class="card" style="width: 100%;">
            <div class="dashboard-header">
                <h2>Daftar Pesanan Terbaru</h2>
                <span class="meta">Total <?php echo count($orders); ?> Pesanan</span>
            </div>

            <div class="table-responsive">
                <table class="order-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nama Pelanggan</th>
                            <th>No. HP</th>
                            <th>Alamat</th>
                            <th>Total</th>
                            <th>Pembayaran</th>
                            <th>Detail Pesanan</th>
                            <th>Waktu Pesan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($orders)): ?>
                            <tr><td colspan="8" style="text-align: center;">Belum ada pesanan yang tersimpan di database.</td></tr>
                        <?php endif; ?>

                        <?php foreach ($orders as $order): 
                            $details = json_decode($order['order_details'], true);
                        ?>
                        <tr>
                            <td><?php echo $order['id']; ?></td>
                            <td><?php echo htmlspecialchars($order['customer_name']); ?></td>
                            <td><?php echo htmlspecialchars($order['customer_phone']); ?></td>
                            <td><?php echo nl2br(htmlspecialchars($order['customer_address'])); ?></td>
                            <td>Rp <?php echo number_format($order['total_amount'], 0, ',', '.'); ?></td>
                            <td><?php echo htmlspecialchars($order['payment_method']); ?></td>
                            <td>
                                <?php if ($details): ?>
                                    <ul style="padding-left: 15px; margin: 0;">
                                        <?php foreach ($details as $item): ?>
                                            <li><?php echo $item['qty'] . ' x ' . htmlspecialchars($item['product']); ?> (Rp <?php echo number_format($item['total'], 0, ',', '.'); ?>)</li>
                                        <?php endforeach; ?>
                                    </ul>
                                <?php else: ?>
                                    *Data tidak valid*
                                <?php endif; ?>
                            </td>
                            <td><?php echo date('d M Y H:i', strtotime($order['order_date'])); ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <footer>
        © "Hidup sehat dimulai dari makanan sehat, dan sayur adalah salah satunya".
    </footer>
</body>
</html>