<?php $role = $_GET['role'] ?? null; ?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin - HCTS</title>
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

    <main class="admin-section">
        <div class="container status-container">
            <div class="login-card">
                <div class="login-icon icon-purple">
                    <svg width="45" height="45" viewBox="0 0 24 24" fill="none" stroke="#7e22ce" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
                        <circle cx="12" cy="11" r="3"></circle>
                    </svg>
                </div>
                
                <h2 class="status-title">Administrator</h2>

                <div class="status-form">
                    <form action="../../app/proses_login.php" method="POST">
                        <div class="form-group">
                            <input type="hidden" name="role" value="<?= htmlspecialchars($role ?? '') ?>">
                            <label for="username">Username</label>
                            <input type="text" id="username" name="login_input" class="form-input" placeholder="Masukkan username">
                        </div>
                        
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" id="password" name="password" class="form-input" placeholder="Masukkan password">
                        </div>

                        <button type="submit" class="btn-purple">Masuk Admin</button>
                        <a href="../MainLogin.php" class="btn-outline-dark">Ganti Role</a>
                    </form>
                </div>
            </div>
        </div>
    </main>

</body>
</html>
