<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pendaftaran Calon Siswa</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../../style/pendaftaran.css">
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar">
        <div class="nav-brand">HCTS</div>
        <ul class="nav-menu">
            <li><a href="#">Home</a></li>
            <li><a href="#">About</a></li>
            <li><a href="#">Programs</a></li>
            <li><a href="#">Admission</a></li>
        </ul>
        <a href="login.php" class="btn-login">Login</a>
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
                    <select name="program" class="form-control" required>
                        <option value="">-- Pilih Program --</option>
                        <option value="Hotel Management">Hotel Management</option>
                        <option value="Cruise Ship Operations">Cruise Ship Operations</option>
                        <option value="Culinary Arts">Culinary Arts</option>
                        <option value="House Keeping">House Keeping</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label>Dokumen Pendaftaran <span class="required">*</span></label>
                    <div class="upload-info">
                        <i class="fas fa-info-circle"></i>
                        <span>Sebelum mengunggah, silakan unduh template <a href="#">Surat Pernyataan</a> di bawah ini. Isi dengan lengkap dan tanda tangani, kemudian scan/foto dan unggah kembali.<br>
                        <a href="#"><i class="fas fa-download"></i> Unduh Surat Pernyataan (.pdf)</a></span>
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

    <!-- Footer -->
    <footer class="footer">
        <div class="footer-grid">
            <div class="footer-col brand-col">
                <h3 class="footer-logo">HCTS</h3>
                <p>Sekolah pelatihan internasional terkemuka untuk karier di bidang perhotelan dan kapal pesiar.</p>
                <div class="social-icons" style="margin-top: 1rem;">
                    <a href="#" style="color: #ccc; margin-right: 15px;"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" style="color: #ccc; margin-right: 15px;"><i class="fab fa-instagram"></i></a>
                    <a href="#" style="color: #ccc; margin-right: 15px;"><i class="fab fa-linkedin-in"></i></a>
                    <a href="#" style="color: #ccc;"><i class="fab fa-twitter"></i></a>
                </div>
            </div>
            <div class="footer-col">
                <h4>Aksi Cepat</h4>
                <ul>
                    <li><a href="#">About Us</a></li>
                    <li><a href="#">Our Programs</a></li>
                    <li><a href="#">Admission Process</a></li>
                    <li><a href="#">Career Opportunities</a></li>
                    <li><a href="#">Student Stories</a></li>
                </ul>
            </div>
            <div class="footer-col">
                <h4>Program Kami</h4>
                <ul>
                    <li><a href="#">Hotel Management</a></li>
                    <li><a href="#">Cruise Ship Operations</a></li>
                    <li><a href="#">Culinary Arts</a></li>
                    <li><a href="#">Hospitality Services</a></li>
                    <li><a href="#">Tourism Management</a></li>
                </ul>
            </div>
            <div class="footer-col contact-col">
                <h4>Kontak Kami</h4>
                <ul>
                    <li><a href="#"><i class="fas fa-map-marker-alt" style="margin-right: 10px;"></i> 123 Maritime Avenue,<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Harbor District, HD 12345</a></li>
                    <li><a href="#"><i class="fas fa-phone-alt" style="margin-right: 10px;"></i> +1 (555) 123-4567</a></li>
                    <li><a href="#"><i class="fas fa-envelope" style="margin-right: 10px;"></i> info@hcts.edu</a></li>
                </ul>
            </div>
        </div>
        <div class="footer-bottom">
            <div class="footer-copy">&copy; 2025 HCTS International. All rights reserved.</div>
            <div class="footer-legal">
                <a href="#">Privacy Policy</a>
                <a href="#">Terms of Service</a>
                <a href="#">Cookie Policy</a>
            </div>
        </div>
    </footer>

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
