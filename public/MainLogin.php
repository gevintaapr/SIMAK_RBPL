<?php include __DIR__ . '/header.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../style/StyleMainLogin.css">
    <title>Login Role</title>
</head>
<body>
    <main class="portal-section">
        <div class="container text-center">
            <h1 class="portal-title">Portal Akademik HCTS</h1>
            <p class="portal-subtitle">Silahkan pilih peran Anda untuk melanjutkan login.</p>

            <div class="role-cards-container">
                <a href="login/logSiswa.php?role=1" class="role-card">
                    <div class="role-icon icon-siswa">
                        👨‍🎓 </div>
                    <h3>Siswa</h3>
                    <p>Akses materi, nilai, & jadwal.</p>
                </a>

                <a href="login/logCalonSiswa.php?role=2" class="role-card">
                    <div class="role-icon icon-calon">
                        🔍
                    </div>
                    <h3>Calon Siswa</h3>
                    <p>Akses materi, nilai, & jadwal.</p>
                </a>

                <a href="login/logPengajar.php?role=3" class="role-card">
                    <div class="role-icon icon-pengajar">
                        📖
                    </div>
                    <h3>Pengajar</h3>
                    <p>Akses materi, nilai, & jadwal.</p>
                </a>

                <a href="login/logPimpinan.php?role=4" class="role-card">
                    <div class="role-icon icon-pimpinan">
                        👔
                    </div>
                    <h3>Pimpinan</h3>
                    <p>Akses materi, nilai, & jadwal.</p>
                </a>

                <a href="login/logAdmin.php?role=5" class="role-card">
                    <div class="role-icon icon-admin">
                        🛡️
                    </div>
                    <h3>Admin</h3>
                    <p>Akses materi, nilai, & jadwal.</p>
                </a>
            </div>

            <a href="MainPage.php" class="btn-outline-primary">
                Kembali ke Halaman Utama 
                <span class="arrow-icon">→</span>
            </a>
        </div>
    </main>
</body>

<?php include __DIR__ . '/footer.php'; ?>