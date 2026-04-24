<?php
require_once '../../config/config.php';
$query_program = mysqli_query($conn, "SELECT * FROM program ORDER BY nama_program ASC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pendaftaran Calon Siswa</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../../style/pendaftaran.css">
    <link rel="stylesheet" href="../../style/dashboard_siswa.css">
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar">
        <div class="nav-brand">HCTS</div>
        <ul class="nav-menu">
            <li><a href="../../public/index.php" class="active">Home</a></li>
            <li><a href="#">About</a></li>
            <li><a href="#">Programs</a></li>
        </ul>
        <div class="nav-action">
            <a href="../../public/login/logSiswa.php" class="btn-logout">Login</a>
        </div>
    </nav>

    <!-- Hero Section -->
    <header class="hero-section">
        <div class="hero-overlay"></div>
        <div class="hero-content">
            <div class="breadcrumb">Beranda &gt; Pendaftaran</div>
            <h1>Pendaftaran Calon Siswa</h1>
        </div>
    </header>

    <!-- Main Content -->
    <main class="main-container">
        <!-- Form Section -->
        <div class="content-section">
            <h2>Formulir Pendaftaran</h2>
            <p class="subtitle">Lengkapi formulir di bawah ini untuk mendaftar sebagai calon siswa HCTS</p>
            
            <form action="../../app/proses_daftar.php" method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label>Nama Lengkap <span class="required">*</span></label>
                    <input type="text" name="nama" class="form-control" placeholder="Masukkan nama lengkap Anda" required>
                </div>
                
                <div class="form-group">
                    <label>Email <span class="required">*</span></label>
                    <input type="email" name="email" class="form-control" placeholder="contoh@gmail.com" required>
                </div>
                
                <div class="form-group">
                    <label>Nomor Whatsapp <span class="required">*</span></label>
                    <input type="text" name="no_wa" class="form-control" placeholder="+62 812 3456 7890" required>
                </div>
                
                <div class="form-group">
                    <label>Tanggal Lahir <span class="required">*</span></label>
                    <input type="date" name="tanggal_lahir" class="form-control" required>
                </div>
                
                <div class="form-group">
                    <label>Alamat Lengkap <span class="required">*</span></label>
                    <textarea name="alamat" class="form-control" required></textarea>
                </div>
                
                <div class="form-group">
                    <label>Pilihan Program <span class="required">*</span></label>
                    <select name="id_program" class="form-control" required>
                        <option value="">-- Pilih Program --</option>
                        <?php while($p = mysqli_fetch_assoc($query_program)): ?>
                            <option value="<?= $p['id_program'] ?>"><?= htmlspecialchars($p['nama_program']) ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>
                
                <div class="form-group">
                    <label>Dokumen Pendaftaran <span class="required">*</span></label>
                    <div class="upload-info">
                        <i class="fas fa-info-circle"></i>
                        <span>Sebelum mengunggah, silakan unduh template <a href="download_template.php?code=surat_pernyataan" target="_blank">Surat Pernyataan</a> di bawah ini. Isi dengan lengkap dan tanda tangani, kemudian scan/foto dan unggah kembali.<br>
                        <a href="download_template.php?code=surat_pernyataan" target="_blank"><i class="fas fa-download"></i> Unduh Surat Pernyataan (.pdf)</a></span>
                    </div>
                    
                    <div class="upload-grid">
                        <div class="upload-item" style="flex-direction: column; align-items: flex-start; gap: 8px;">
                            <label><i class="fas fa-file-pdf"></i> 1. Scan KTP</label>
                            <input type="file" name="ktp" accept=".pdf" required style="width: 100%;">
                        </div>
                        <div class="upload-item" style="flex-direction: column; align-items: flex-start; gap: 8px;">
                            <label><i class="fas fa-file-pdf"></i> 2. Ijazah Terakhir</label>
                            <input type="file" name="ijazah" accept=".pdf" required style="width: 100%;">
                        </div>
                        <div class="upload-item" style="flex-direction: column; align-items: flex-start; gap: 8px;">
                            <label><i class="fas fa-image"></i> 3. Pas Foto (JPG/PNG)</label>
                            <input type="file" name="foto_siswa" accept=".jpg,.jpeg,.png" required style="width: 100%;">
                        </div>
                        <div class="upload-item" style="flex-direction: column; align-items: flex-start; gap: 8px;">
                            <label><i class="fas fa-file-pdf"></i> 4. Bukti Pembayaran</label>
                            <input type="file" name="bukti_pendaftaran" accept=".pdf" required style="width: 100%;">
                        </div>
                        <div class="upload-item full-width" style="flex-direction: column; align-items: flex-start; gap: 8px;">
                            <label><i class="fas fa-file-pdf"></i> 5. Surat Pernyataan</label>
                            <input type="file" name="surat_pernyataan" accept=".pdf" required style="width: 100%;">
                        </div>
                    </div>
                    <div class="upload-note">*Format: PDF/JPG/PNG. Maks: 5MB per file.</div>
                </div>
                
                <div class="agreement">
                    <input type="checkbox" id="agree" required>
                    <label for="agree">Saya menyetujui <a href="#">syarat dan ketentuan</a> yang berlaku serta menyatakan bahwa data yang saya berikan adalah benar.</label>
                </div>
                
                <button type="submit" class="btn-submit">Kirim Pendaftaran</button>
            </form>
        </div>
        
        <!-- Image Section -->
        <div class="image-section">
        </div>
    </main>

    <div style="height: 50px;"></div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const fileInputs = document.querySelectorAll('.upload-item input[type="file"]');
        
        fileInputs.forEach(input => {
            // Find the icon and set initial style
            const icon = input.closest('.upload-item').querySelector('i.fas');
            if (icon) {
                icon.style.color = '#ccc';
                icon.style.transition = 'color 0.3s ease';
            }

            // Update color on change
            input.addEventListener('change', function() {
                if (icon) {
                    if (this.files && this.files.length > 0) {
                        icon.style.color = 'var(--primary-blue, #0056b3)'; // Aktif (Biru)
                    } else {
                        icon.style.color = '#ccc'; // Tidak aktif (Abu-abu)
                    }
                }
            });
        });
    });
    </script>
</body>
</html>
