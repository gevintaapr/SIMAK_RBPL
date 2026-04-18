<?php $role = $_GET['role'] ?? null; ?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Siswa - HCTS</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../../style/StyleLoginRole.css">
</head>
<body>

    <!-- NAVBAR -->
    <header class="navbar">
        <div class="nav-container">
            <div class="logo">HCTS</div>
            <nav>
                <ul class="nav-links">
                    <li><a href="#">Home</a></li>
                    <li><a href="#">About</a></li>
                    <li><a href="#">Programs</a></li>
                    <li><a href="#">Admission</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <main class="login-wrapper">
        <div class="login-card">
            <div class="card-header">
                <div class="icon-placeholder">🎓</div> 
                <h2>Login Siswa</h2>
            </div>
            
            <form action="../../app/proses_login.php" method="POST">
                <div class="input-group">
                    <input type="hidden" name="role" value="1">
                    <label for="email">Email Belajar</label>
                    <input type="email" id="email" name="login_input" class="form-input" placeholder="contoh@hcts.ac.id" required>
                </div>
                
                <div class="input-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required>
                </div>
                
                <button type="submit" class="btn-primary">Masuk Dashboard</button>
                <a href="../MainLogin.php" class="btn-secondary" style="display:block; text-align:center; text-decoration:none; margin-top:15px; padding:12px; border-radius:25px; border:1px solid #023666; color:#023666; font-weight:bold; font-size:14px;">Ganti Role</a>
            </form>
        </div>
    </main>

</body>
</html>