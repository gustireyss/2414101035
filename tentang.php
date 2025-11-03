<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>Tentang Kami - Rey's Vegetables</title>
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
        .card{background:var(--card);border-radius:var(--radius);padding:20px;box-shadow:0 1px 3px rgba(0,0,0,0.1)}
        footer{max-width:var(--maxw);margin:28px auto;text-align:center;color:#555;font-size:13px;padding:6px;}
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
                <a href="tentang.php" class="profile-link" style="background: #eef3f0; color: var(--primary);">Tentang</a>
                <a href="keranjang.php" class="profile-link">Keranjang</a>
                <a href="admin_login.php" class="profile-link">Login Admin</a> 
            </div>
        </div>
    </header>

    <main>
        <div class="card" style="min-height: 400px;">
            <h2>Tentang Rey's Vegetables</h2>
            <p>Kami adalah penyedia sayuran dan bahan makanan segar dari Pasar Cigasong, Majalengka.</p>
            <p>Rey's Vegetables berkomitmen untuk menyediakan bahan makanan berkualitas tinggi, sehat, dan langsung diantar ke rumah Anda.</p>
            <p>Kami melayani pemesanan dengan sistem Bayar di Tempat (COD) dan Transfer Bank.</p>
            <p>Untuk pertanyaan lebih lanjut, silakan hubungi kami melalui WhatsApp di **081312545499**.</p>
        </div>
    </main>
    <footer>
        © "Hidup sehat dimulai dari makanan sehat, dan sayur adalah salah satunya".
    </footer>
</body>
</html>