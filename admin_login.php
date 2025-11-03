<?php
session_start();

// LOGIKA LOGOUT
if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    unset($_SESSION['is_admin_logged_in']);
    session_destroy();
    header("Location: admin_login.php");
    exit();
}
?>
<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>Login Admin - Rey's Vegetables</title>
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
        main{max-width:var(--maxw);margin:24px auto;padding:0 16px; display: flex; justify-content: center; align-items: flex-start; min-height: 60vh;}
        .login-card{background:#fff;border-radius:var(--radius);padding:20px;box-shadow:0 1px 3px rgba(0,0,0,0.1); width: 400px; max-width: 100%;}
        input, button{padding:10px; border-radius:8px; border:1px solid #d1d5db; font-size:14px; width:100%; margin-bottom: 12px;}
        button.full{background:var(--primary); color: #fff; font-weight: 700; cursor: pointer; transition: background 0.2s;}
        button.full:hover{background: #0056b3;}
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
                    <div class="subtitle">Pasar Cigasong Majalengka (Admin)</div>
                </div>
            </div>
            <div class="navbar">
                <a href="index.php" class="profile-link">Produk</a>
                <a href="tentang.php" class="profile-link">Tentang</a>
                <a href="keranjang.php" class="profile-link">Keranjang</a>
                <a href="admin_login.php" class="profile-link" style="background: #eef3f0; color: var(--primary);">Login Admin</a> 
            </div>
        </div>
    </header>

    <main>
        <div class="login-card">
            <h2>Login Administrator</h2>
            
            <?php 
            if (isset($_GET['error']) && $_GET['error'] == 1) {
                echo '<p style="color: red; text-align: center; margin-bottom: 15px;">Username atau Password salah!</p>';
            }
            ?>

            <form action="admin_dashboard.php" method="POST">
                <input type="text" name="username" placeholder="Username" required />
                <input type="password" name="password" placeholder="Password" required />
                <button type="submit" class="full">Masuk</button>
            </form>
            <p style="text-align: center; font-size: 12px; color: #555;">Kredensial Uji Coba: adminrey / sayursehat</p>
        </div>
    </main>
    <footer>
        © "Hidup sehat dimulai dari makanan sehat, dan sayur adalah salah satunya".
    </footer>
</body>
</html>