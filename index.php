<?php
session_start(); 
include 'db_connect.php'; 

// LOGIKA TAMBAH KE KERANJANG
if (isset($_POST['add_to_cart'])) {
    $product_id = $_POST['product_id'];
    
    $stmt = $conn->prepare("SELECT id, name, price, unit, stock FROM products WHERE id = ?");
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $product = $result->fetch_assoc();
    $stmt->close();
    
    if ($product && $product['stock'] > 0) {
        if (!isset($_SESSION['cart'])) { $_SESSION['cart'] = []; }

        $found = false;
        foreach ($_SESSION['cart'] as $key => $cart_item) {
            if ($cart_item['id'] == $product_id) {
                if ($_SESSION['cart'][$key]['qty'] < $product['stock']) {
                    $_SESSION['cart'][$key]['qty'] += 1;
                }
                $found = true;
                break;
            }
        }
        if (!$found) {
            $_SESSION['cart'][] = ['id' => $product['id'], 'name' => $product['name'], 'price' => $product['price'], 'unit' => $product['unit'], 'qty' => 1];
        }
        
        header("Location: index.php"); 
        exit();
    }
}
$conn->close();
?>
<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>Produk - Rey's Vegetables</title>
    <style>
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
        .products{display:grid;grid-template-columns:repeat(auto-fit, minmax(280px, 1fr));gap:16px;}
        .product{display:flex;gap:16px;align-items:center;padding:15px;border-radius:12px;border:1px solid #eef3f0;background:#fff;box-shadow:0 4px 6px rgba(0,0,0,0.05);}
        .thumb img{width:80px;height:80px;border-radius:10px;object-fit:cover;border:1px solid #eee;}
        .pinfo h3{margin:0 0 4px 0;font-size:18px}
        .meta{color:var(--muted);font-size:14px;}
        .price{font-weight:700;color:var(--primary);margin-top:4px;}
        .btn{border:none;padding:10px 15px;border-radius:8px;background:var(--accent);color:#0f172a;font-weight:600;cursor:pointer;transition:background 0.2s;}
        .btn:hover{background:#5acb21;}
        .full{width:100%;padding:12px;border-radius:10px;border:none;background:var(--accent);color:#0f172a;font-weight:700;cursor:pointer;transition:background 0.2s; text-align: center; text-decoration: none; display: block;}
        .cart-empty{color:var(--muted);text-align:center;padding:20px;border:1px dashed #ccc;border-radius:8px;}
        footer{max-width:var(--maxw);margin:28px auto;text-align:center;color:#555;font-size:13px;padding:6px;}
        .cart-item{display:flex;justify-content:space-between;align-items:center;padding:8px;border-bottom:1px solid #eee;}
        .cart-item strong{font-size: 14px;}
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
                <a href="index.php" class="profile-link" style="background: #eef3f0; color: var(--primary);">Produk</a>
                <a href="tentang.php" class="profile-link">Tentang</a>
                <a href="keranjang.php" class="profile-link">Keranjang</a>
                <a href="admin_login.php" class="profile-link">Login Admin</a> 
            </div>
        </div>
    </header>

    <main>
        <div class="grid">
            <section class="card">
                <h2>Pilih Sayuran Segar Anda</h2>
                <p class="meta">Pesan sekarang, kami antar sampai ke rumah! Harga per satuan.</p>

                <div class="products" role="list">
                    <?php
                    include 'db_connect.php'; 
                    $sql = "SELECT * FROM products ORDER BY name ASC";
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        while($p = $result->fetch_assoc()) {
                            $stok_text = ($p['stock'] > 0) ? $p['stock'] . ' ' . $p['unit'] : 'Habis';
                            $disabled = ($p['stock'] <= 0) ? 'disabled' : '';
                            $price_formatted = number_format($p['price'], 0, ',', '.');
                            
                            echo '
                            <article class="product">
                                <div class="thumb">
                                    <img src="' . htmlspecialchars($p['image_url']) . '" alt="' . htmlspecialchars($p['name']) . '">
                                </div>
                                <div class="pinfo">
                                    <h3>' . htmlspecialchars($p['name']) . '</h3>
                                    <div class="meta">Stok: ' . $stok_text . '</div>
                                    <div class="price">Rp ' . $price_formatted . ' / ' . htmlspecialchars($p['unit']) . '</div>
                                    <div class="paction">
                                        <form method="POST" action="index.php">
                                            <input type="hidden" name="product_id" value="' . $p['id'] . '">
                                            <button type="submit" name="add_to_cart" class="btn" ' . $disabled . '>Tambah ke Keranjang</button>
                                        </form>
                                    </div>
                                </div>
                            </article>';
                        }
                    } else {
                        echo "<p>Belum ada produk yang tersedia.</p>";
                    }
                    $conn->close();
                    ?>
                </div>
            </section>

            <aside class="card">
                <h2>Keranjang Belanja</h2>
                <?php
                $cart_subtotal = 0;
                $is_cart_empty = empty($_SESSION['cart']);
                ?>
                <div class="cart-items">
                    <?php if (!$is_cart_empty): ?>
                        <?php foreach ($_SESSION['cart'] as $item): 
                            $item_total = $item['price'] * $item['qty'];
                            $cart_subtotal += $item_total;
                        ?>
                            <div class="cart-item">
                                <strong><?php echo htmlspecialchars($item['name']); ?></strong>
                                <span><?php echo $item['qty'] . ' ' . htmlspecialchars($item['unit']); ?></span>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="cart-empty">Keranjang Anda masih kosong.</div>
                    <?php endif; ?>
                </div>

                <div class="total">
                    <div class="total-row"><div>Subtotal</div><div>Rp <?php echo number_format($cart_subtotal, 0, ',', '.'); ?></div></div>
                </div>
                <a href="keranjang.php" class="full" style="margin-top: 15px; <?php echo $is_cart_empty ? 'pointer-events: none; opacity: 0.5;' : ''; ?>">Lanjutkan ke Keranjang</a>
            </aside>
        </div>
    </main>
    <footer>
        © "Hidup sehat dimulai dari makanan sehat, dan sayur adalah salah satunya".
    </footer>
</body>
</html>