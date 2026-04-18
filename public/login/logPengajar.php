<?php $role = $_GET['role'] ?? null; ?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Pengajar - HCTS</title>
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

    <main class="pengajar-section">
        <div class="container status-container">
            <div class="login-card">
                <div class="login-icon icon-green">
                    <svg width="45" height="45" viewBox="0 0 24 24" fill="none" stroke="#10b981" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"></path>
                        <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"></path>
                    </svg>
                </div>
                
                <h2 class="status-title">Portal Pengajar</h2>
                
                <form action="../../app/proses_login.php" method="POST" class="status-form">
                    <div class="form-group">
                        <input type="hidden" name="role" value="3">                        
                        <label for="username">NIDN / Username</label>
                        <input type="text" id="username" name="login_input" class="form-input" placeholder="Masukkan NIDN atau username">
                    </div>
                    
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" id="password" name="password" class="form-input" placeholder="Masukkan password">
                    </div>

                    <button type="submit" class="btn-gold">Masuk Pengajar</button>
                    <a href="../MainLogin.php" class="btn-outline-dark">Ganti Role</a>
                </form>
            </div>
        </div>
    </main>

</body>
</html>