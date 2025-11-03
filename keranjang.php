<?php
session_start();
include 'db_connect.php'; 

// LOGIKA UPDATE KUANTITAS DAN HAPUS ITEM (Sama seperti sebelumnya)
if (isset($_POST['update_cart']) || isset($_POST['remove_item'])) {
    if (isset($_POST['update_cart'])) {
        $product_id = $_POST['product_id'];
        $change = isset($_POST['qty_change']) ? intval($_POST['qty_change']) : 0;
        
        if (isset($_SESSION['cart'])) {
            foreach ($_SESSION['cart'] as $key => $item) {
                if ($item['id'] == $product_id) {
                    $product_stock = 100; // Placeholder
                    
                    $_SESSION['cart'][$key]['qty'] += $change;
                    
                    if ($_SESSION['cart'][$key]['qty'] <= 0) {
                        unset($_SESSION['cart'][$key]); 
                    } elseif ($_SESSION['cart'][$key]['qty'] > $product_stock) {
                        $_SESSION['cart'][$key]['qty'] = $product_stock; 
                    }

                    $_SESSION['cart'] = array_values($_SESSION['cart']); 
                    break;
                }
            }
        }
    }
    if (isset($_POST['remove_item'])) {
        $product_id = $_POST['product_id'];
        if (isset($_SESSION['cart'])) {
            foreach ($_SESSION['cart'] as $key => $item) {
                if ($item['id'] == $product_id) {
                    unset($_SESSION['cart'][$key]);
                    $_SESSION['cart'] = array_values($_SESSION['cart']); 
                    break;
                }
            }
        }
    }
    header("Location: keranjang.php");
    exit();
}
$conn->close();

$subtotal = 0;
?>
<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>Keranjang Belanja - Rey's Vegetables</title>
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
        main{max-width:var(--maxw);margin:24px auto;padding:0 16px}
        .grid{display:grid;grid-template-columns:1fr 360px;gap:20px;align-items:start;}
        .card{background:var(--card);border-radius:var(--radius);padding:20px;box-shadow:0 1px 3px rgba(0,0,0,0.1)}
        .cart-item{display:flex;justify-content:space-between;align-items:center;padding:12px;border-radius:8px;border:1px solid #eef3f0;margin-bottom:8px;background:#f9f9f9;}
        .item-info strong{display:block;margin-bottom:2px;}
        .item-info small{color:var(--muted);font-size:12px;}
        .item-qty{display:flex;align-items:center;gap:8px;}
        .qty-btn{background:var(--primary);color:#fff;border:none;width:24px;height:24px;border-radius:4px;cursor:pointer;}
        .qty-btn.remove{background:#ef4444;}
        .qty-btn:hover{opacity:0.9;}
        .total{display:flex;flex-direction:column;gap:4px;border-top:1px solid #eee;padding-top:12px;margin-top:12px;}
        .total-row{display:flex;justify-content:space-between;align-items:center;}
        .total-row div:last-child{font-weight:700;color:var(--primary);}
        form.checkout{display:flex;flex-direction:column;gap:12px;margin-top:20px;padding-top:10px;border-top:1px solid #eee;}
        input,textarea,select{padding:10px;border-radius:8px;border:1px solid #d1d5db;font-size:14px;width:100%;}
        .full{width:100%;padding:12px;border-radius:10px;border:none;background:var(--accent);color:#0f172a;font-weight:700;cursor:pointer;transition:background 0.2s; text-align: center; text-decoration: none; display: block;}
        .cart-empty{color:var(--muted);text-align:center;padding:20px;border:1px dashed #ccc;border-radius:8px;}
        footer{max-width:var(--maxw);margin:28px auto;text-align:center;color:#555;font-size:13px;padding:6px;}
        @media (max-width:980px){.grid{grid-template-columns:1fr;}}
    </style>
</head>
<body>
    <header>
        <div class="wrap">
            <div class="brand">
                <div><img src="Presiden Konoha.jpg" alt="logo Profile" width="90"></div>
                <div>
                    <h1>Rey's Vegetables</h1>
                    <div class="subtitle">Pasar Cigasong Majalengka</div>
                    <div class="subtitle">081312545499 (Gusti Reyhans)</div>
                </div>
            </div>
            <div class="navbar">
                <a href="index.php" class="profile-link">Produk</a>
                <a href="tentang.php" class="profile-link">Tentang</a>
                <a href="keranjang.php" class="profile-link" style="background: #eef3f0; color: var(--primary);">Keranjang</a>
                <a href="admin_login.php" class="profile-link">Login Admin</a> 
            </div>
        </div>
    </header>

    <main>
        <div class="grid">
            <section class="card">
                <h2>Ringkasan Pesanan</h2>
                <div class="cart-items">
                    <?php if (!empty($_SESSION['cart'])): ?>
                        <?php foreach ($_SESSION['cart'] as $item):
                            $item_total = $item['price'] * $item['qty'];
                            $subtotal += $item_total;
                    ?>
                        <div class="cart-item">
                            <div class="item-info">
                                <strong><?php echo htmlspecialchars($item['name']); ?></strong>
                                <small>Rp <?php echo number_format($item['price'], 0, ',', '.'); ?> / <?php echo htmlspecialchars($item['unit']); ?></small>
                                <small>Total: Rp <?php echo number_format($item_total, 0, ',', '.'); ?></small>
                            </div>
                            <div class="item-qty">
                                <form method="POST" style="display:inline;">
                                    <input type="hidden" name="product_id" value="<?php echo $item['id']; ?>">
                                    <input type="hidden" name="qty_change" value="-1">
                                    <button class="qty-btn remove" name="update_cart" type="submit">-</button>
                                </form>

                                <span><?php echo $item['qty'] . ' ' . htmlspecialchars($item['unit']); ?></span>

                                <form method="POST" style="display:inline;">
                                    <input type="hidden" name="product_id" value="<?php echo $item['id']; ?>">
                                    <input type="hidden" name="qty_change" value="1">
                                    <button class="qty-btn" name="update_cart" type="submit">+</button>
                                </form>
                            </div>
                        </div>
                    <?php endforeach; ?>
                    <?php else: ?>
                        <div class="cart-empty">Keranjang Anda masih kosong. Yuk, pilih sayuran segar!</div>
                    <?php endif; ?>
                </div>
            </section>

            <aside class="card">
                <h2>Lengkapi Data & Checkout</h2>
                <div class="total">
                    <div class="total-row"><div>Subtotal</div><div id="subtotal">Rp <?php echo number_format($subtotal, 0, ',', '.'); ?></div></div>
                    <div class="total-row"><div class="subtitle">Biaya Pengiriman (COD)</div><div class="subtitle">Dihitung saat konfirmasi</div></div>
                    <div class="total-row"><div>Total Estimasi</div><div id="grand-total">Rp <?php echo number_format($subtotal, 0, ',', '.'); ?></div></div>
                </div>

                <form class="checkout" method="POST" action="checkout_process.php">
                    <p style="font-size:14px; font-weight:600; margin-bottom:0;">Data Pengiriman</p>
                    <input type="text" name="input_name" placeholder="Nama lengkap" required />
                    <input type="tel" name="input_phone" placeholder="Nomor HP (WhatsApp)" required />
                    <textarea name="input_address" placeholder="Alamat pengantaran lengkap" rows="3" required></textarea>
                    
                    <select name="input_payment_method">
                        <option value="COD">Bayar di Tempat (COD)</option>
                        <option value="Transfer">Transfer Bank</option>
                    </select>
                    <button type="submit" class="full" <?php echo empty($_SESSION['cart']) ? 'disabled' : ''; ?>>
                        Buat Pemesanan (Rp <?php echo number_format($subtotal, 0, ',', '.'); ?>)
                    </button>
                </form>
            </aside>
        </div>
    </main>
    <footer>
        © "Hidup sehat dimulai dari makanan sehat, dan sayur adalah salah satunya".
    </footer>
</body>
</html>