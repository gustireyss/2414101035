<?php
session_start();
include 'db_connect.php'; 

$message_title = "ERROR PEMESANAN";
$message_body = "Terjadi kesalahan saat memproses pesanan Anda atau keranjang belanja kosong.";
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_SESSION['cart'])) {
    
    // 1. Ambil data POST dan hitung total
    $name = $conn->real_escape_string($_POST['input_name']);
    $phone = $conn->real_escape_string($_POST['input_phone']);
    $address = $conn->real_escape_string($_POST['input_address']);
    $payment = $conn->real_escape_string($_POST['input_payment_method']);

    $total_order = 0;
    $order_details_array = [];
    foreach ($_SESSION['cart'] as $item) {
        $item_total = $item['price'] * $item['qty'];
        $total_order += $item_total;
        $order_details_array[] = [
            'product' => $item['name'],
            'qty' => $item['qty'],
            'price' => $item['price']
        ];
    }
    $order_details_json = json_encode($order_details_array);

    // 2. Simpan ke database
    $stmt = $conn->prepare("INSERT INTO orders (customer_name, customer_phone, customer_address, total_amount, payment_method, order_details) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssdss", $name, $phone, $address, $total_order, $payment, $order_details_json);
    
    if ($stmt->execute()) {
        $success = true;
        $order_id = $stmt->insert_id;
        $message_title = "PESANAN BERHASIL DIBUAT!";
        $message_body = "Terima kasih, " . htmlspecialchars($name) . "! Pesanan Anda telah kami simpan dengan Nomor Pesanan: " . $order_id . ".\n\n" .
                        "Total Estimasi: Rp " . number_format($total_order, 0, ',', '.') . "\n" .
                        "Kami akan segera menghubungi Anda untuk konfirmasi pengiriman.";
        
        // 3. Hapus keranjang setelah pemesanan sukses
        unset($_SESSION['cart']);

    } else {
        $message_body = "Gagal menyimpan pesanan ke database. Error: " . $conn->error;
    }

    $stmt->close();
}
$conn->close();
?>
<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>Pemesanan Berhasil - Rey's Vegetables</title>
    <style>
        /* --- CSS LENGKAP DARI INDEX.PHP --- */
        :root{ --bg:#fcf9f9; --card:#fff; --accent:#6ce82e; --primary:#080909; --muted:#45df09; --maxw:1100px; --radius:12px; }
        *{box-sizing:border-box}
        body{margin:0;font-family:Inter, system-ui, -apple-system, 'Segoe UI', Roboto, 'Helvetica Neue', Arial;color:#0a0101;background:var(--bg)}
        header{background:linear-gradient(90deg,var(--primary),#4a99ed);padding:20px 16px;border-bottom:1px solid var(--primary)}
        .wrap{max-width:var(--maxw);margin:0 auto;display:flex;align-items:center;justify-content:space-between;gap:16px;color:#fff}
        .brand{display:flex;align-items:center;gap:12px}
        h1{font-size:24px;margin:0;color:#fff}
        .subtitle{color:#eef3f0;font-size:14px}
        .navbar{display:flex; align-items:center;}
        .profile-link{color:#fff;text-decoration:none;font-weight:600;font-size:14px; margin-left: 20px; padding: 5px 10px; border-radius: 6px; transition: background 0.2s;}
        .profile-link:hover{background: rgba(255, 255, 255, 0.1);}
        main{max-width:var(--maxw);margin:24px auto;padding:0 16px; display: flex; justify-content: center; align-items: center; min-height: 60vh;}
        .card-success{max-width: 600px; text-align: center; padding: 40px; border: 2px solid <?php echo $success ? 'var(--accent)' : 'red'; ?>; background: <?php echo $success ? '#f0fff0' : '#fff0f0'; ?>; border-radius: 12px;}
        .card-success h2{color: <?php echo $success ? 'var(--accent)' : 'red'; ?>;}
        .card-success p{white-space: pre-wrap; text-align: left; background: #fff; padding: 15px; border-radius: 8px;}
        .full{background: var(--primary); color:#fff !important;}
        footer{max-width:var(--maxw);margin:28px auto;text-align:center;color:#555;font-size:13px;padding:6px;}
        /* ... CSS lainnya ... */
    </style>
</head>
<body>
    <header>
        <div class="wrap">
            <div class="brand">
                <div><img src="calon bos.jpg" alt="logo Profile" width="90"></div>
                <div>
                    <h1>Rey's Vegetables</h1>
                    <div class="subtitle">Pasar Cigasong Majalengka</div>
                    <div class="subtitle">081312545499 (Gusti Reyhans)</div>
                </div>
            </div>
            <div class="navbar">
                <a href="index.php" class="profile-link">Produk</a>
                <a href="tentang.php" class="profile-link">Tentang</a>
                <a href="keranjang.php" class="profile-link">Keranjang</a>
                <a href="admin_login.php" class="profile-link">Login Admin</a> 
            </div>
        </div>
    </header>

    <main>
        <div class="card-success">
            <h2><?php echo $message_title; ?></h2>
            <p><?php echo $message_body; ?></p>
            <a href="index.php" class="full" style="margin-top: 20px;">Kembali ke Halaman Produk</a>
        </div>
    </main>
    <footer>
        © "Hidup sehat dimulai dari makanan sehat, dan sayur adalah salah satunya".
    </footer>
</body>
</html>