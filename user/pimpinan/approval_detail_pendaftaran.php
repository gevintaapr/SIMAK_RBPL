<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Pendaftaran: Alexander Wibowo</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="../../style/approval.css?v=<?= time() ?>">
</head>
<body>

    <!-- ===== SIDEBAR ===== -->
    <aside class="sidebar collapsed" id="sidebar">
        <div class="sidebar-header">
            <span class="sidebar-logo">HCTS</span>
        </div>
        <nav class="sidebar-nav">
            <a href="dashboard_pimpinan.php" class="sidebar-link">
                <i class="fa-solid fa-gauge-high"></i>
                <span>Dashboard</span>
            </a>
            <a href="#" class="sidebar-link">
                <i class="fa-solid fa-users"></i>
                <span>Daftar Siswa</span>
            </a>
            <a href="approval.php" class="sidebar-link active">
                <i class="fa-solid fa-check-double"></i>
                <span>Approval</span>
            </a>
        </nav>
        <div class="sidebar-footer">
            <a href="#" class="sidebar-link sidebar-logout">
                <i class="fa-solid fa-right-from-bracket"></i>
                <span>Logout</span>
            </a>
        </div>
    </aside>

    <!-- ===== MAIN WRAPPER ===== -->
    <div class="main-wrapper sidebar-collapsed" id="mainWrapper">

        <!-- ===== NAVBAR ===== -->
        <header class="navbar">
            <div class="navbar-left">
                <button class="sidebar-toggle" id="sidebarToggle" aria-label="Toggle Sidebar">
                    <span></span>
                    <span></span>
                    <span></span>
                </button>
                <span class="navbar-brand">HCTS Executive</span>
            </div>
            <div class="navbar-right">
                <button class="notif-btn" id="notifBtn" aria-label="Notifikasi">
                    <i class="fa-regular fa-bell"></i>
                </button>
                <div class="admin-profile">
                    <div class="admin-avatar">GN</div>
                    <div class="admin-info">
                        <span class="admin-name">Pimpinan</span>
                        <span class="admin-role">Direktur Utama</span>
                    </div>
                </div>
            </div>
        </header>

        <!-- ===== PAGE CONTENT ===== -->
        <main class="page-content">

            <!-- Hero / Dashboard Banner -->
            <section class="dashboard-banner">
                <div class="banner-content">
                    <div class="banner-text-wrapper">
                        <p class="breadcrumb"><a href="approval.php">Approval: Pendaftaran</a> &gt; Detail</p>
                        <h1 class="banner-title">Detail: Alexander Wibowo</h1>
                    </div>
                </div>
            </section>

            <!-- Detail Section -->
            <section class="detail-section">
                <!-- Card 1: Informasi Pribadi -->
                <div class="detail-card">
                    <h2 class="card-title-detail">Informasi Pribadi</h2>
                    <hr class="card-divider">
                    <div class="card-body-detail">
                        <div class="info-content">
                            <div class="info-grid">
                                <div class="info-item">
                                    <span class="info-label">Nama Lengkap</span>
                                    <span class="info-value">Alexander Wibowo</span>
                                </div>
                                <div class="info-item">
                                    <span class="info-label">Email</span>
                                    <span class="info-value">alex.bow23@gmail.com</span>
                                </div>
                                <div class="info-item">
                                    <span class="info-label">Nomor Whatsapp</span>
                                    <span class="info-value">+62 812 3456 7890</span>
                                </div>
                                <div class="info-item">
                                    <span class="info-label">Tanggal Lahir</span>
                                    <span class="info-value">22 Oktober 2004</span>
                                </div>
                                <div class="info-item">
                                    <span class="info-label">Asal Sekolah</span>
                                    <span class="info-value">SMK Pariwisata Jaya</span>
                                </div>
                                <div class="info-item">
                                    <span class="info-label">Posisi-Program Pilihan</span>
                                    <span class="info-value">Hotel-F&amp;B Service</span>
                                </div>
                                <div class="info-item full-width">
                                    <span class="info-label">Alamat Lengkap</span>
                                    <span class="info-value">Jl. Samudra Biru No. 45, Kuta, Bali, Indonesia</span>
                                </div>
                                <div class="info-item full-width mt-2">
                                    <span class="info-label">Status Pendaftaran:</span>
                                    <span class="badge-status-pending">Menunggu Persetujuan</span>
                                </div>
                            </div>
                        </div>
                        <div class="photo-content">
                            <img src="https://ui-avatars.com/api/?name=Alexander+Wibowo&background=0D8ABC&color=fff&size=200" alt="Foto Calon Siswa" class="student-photo">
                            <p class="photo-title">Foto Calon Siswa</p>
                            <p class="photo-date">Tanggal Upload: 10/10/2024</p>
                        </div>
                    </div>
                </div>

                <!-- Card 2: Dokumen Pendaftaran -->
                <div class="detail-card">
                    <h2 class="card-title-detail">Dokumen Pendaftaran</h2>
                    <hr class="card-divider">
                    <div class="doc-grid">
                        <!-- Surat Pernyataan -->
                        <div class="doc-card">
                            <div class="doc-icon">
                                <i class="fa-regular fa-file-lines"></i>
                            </div>
                            <div class="doc-info">
                                <h3 class="doc-name">Surat Pernyataan</h3>
                                <p class="doc-filename">surat_pernyataanHCTS.pdf</p>
                                <div class="doc-actions">
                                    <button class="btn-doc btn-view"><i class="fa-regular fa-eye"></i> Lihat</button>
                                    <button class="btn-doc btn-download"><i class="fa-solid fa-download"></i> Unduh</button>
                                </div>
                            </div>
                        </div>
                        <!-- KTP -->
                        <div class="doc-card">
                            <div class="doc-icon">
                                <i class="fa-regular fa-id-card"></i>
                            </div>
                            <div class="doc-info">
                                <h3 class="doc-name">KTP</h3>
                                <p class="doc-filename">ktp_alex.pdf</p>
                                <div class="doc-actions">
                                    <button class="btn-doc btn-view"><i class="fa-regular fa-eye"></i> Lihat</button>
                                    <button class="btn-doc btn-download"><i class="fa-solid fa-download"></i> Unduh</button>
                                </div>
                            </div>
                        </div>
                        <!-- Ijazah -->
                        <div class="doc-card">
                            <div class="doc-icon">
                                <i class="fa-solid fa-graduation-cap"></i>
                            </div>
                            <div class="doc-info">
                                <h3 class="doc-name">Ijazah</h3>
                                <p class="doc-filename">ijazah_alex.pdf</p>
                                <div class="doc-actions">
                                    <button class="btn-doc btn-view"><i class="fa-regular fa-eye"></i> Lihat</button>
                                    <button class="btn-doc btn-download"><i class="fa-solid fa-download"></i> Unduh</button>
                                </div>
                            </div>
                        </div>
                        <!-- Bukti Pembayaran -->
                        <div class="doc-card">
                            <div class="doc-icon">
                                <i class="fa-solid fa-file-invoice-dollar"></i>
                            </div>
                            <div class="doc-info">
                                <h3 class="doc-name">Bukti Pembayaran</h3>
                                <p class="doc-filename">Pembayaran_Pendaftaran.pdf</p>
                                <div class="doc-actions">
                                    <button class="btn-doc btn-view"><i class="fa-regular fa-eye"></i> Lihat</button>
                                    <button class="btn-doc btn-download"><i class="fa-solid fa-download"></i> Unduh</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Verify Button -->
                <div class="action-section">
                    <button class="btn-reject">Tolak Pendaftaran</button>
                    <button class="btn-approve">Setujui Pendaftaran</button>
                </div>
            </section>
            
        </main>

        <!-- ===== FOOTER ===== -->
        <footer class="footer">
            <div class="footer-top">
                <div class="footer-col footer-brand-col">
                    <h3 class="footer-brand">HCTS</h3>
                    <p class="footer-desc">Sekolah pelatihan internasional terkemuka untuk karier di bidang perhotelan dan kapal pesiar.</p>
                    <div class="footer-socials">
                        <a href="#" class="social-btn" aria-label="Facebook"><i class="fa-brands fa-facebook-f"></i></a>
                        <a href="#" class="social-btn" aria-label="Instagram"><i class="fa-brands fa-instagram"></i></a>
                        <a href="#" class="social-btn" aria-label="YouTube"><i class="fa-brands fa-youtube"></i></a>
                        <a href="#" class="social-btn" aria-label="LinkedIn"><i class="fa-brands fa-linkedin-in"></i></a>
                    </div>
                </div>

                <div class="footer-col">
                    <h4 class="footer-heading">Aksi Cepat</h4>
                    <ul class="footer-links">
                        <li><a href="#">About Us</a></li>
                        <li><a href="#">Our Programs</a></li>
                        <li><a href="#">Admission Process</a></li>
                        <li><a href="#">Career Opportunities</a></li>
                        <li><a href="#">Student Stories</a></li>
                    </ul>
                </div>

                <div class="footer-col">
                    <h4 class="footer-heading">Program Kami</h4>
                    <ul class="footer-links">
                        <li><a href="#">Hotel Management</a></li>
                        <li><a href="#">Cruise Ship Operations</a></li>
                        <li><a href="#">Culinary Arts</a></li>
                        <li><a href="#">Hospitality Services</a></li>
                        <li><a href="#">Tourism Management</a></li>
                    </ul>
                </div>

                <div class="footer-col">
                    <h4 class="footer-heading">Kontak Kami</h4>
                    <ul class="footer-contact">
                        <li><i class="fa-solid fa-location-dot"></i> <span>123 Maritime Avenue, Harbor District, HD 12345</span></li>
                        <li><i class="fa-solid fa-phone"></i> <span>+1 (555) 123-4567</span></li>
                        <li><i class="fa-regular fa-envelope"></i> <span>info@hcts.edu</span></li>
                    </ul>
                </div>
            </div>

            <div class="footer-bottom">
                <p class="footer-copy">&copy; 2025 HCTS International. All rights reserved.</p>
                <div class="footer-legal">
                    <a href="#">Privacy Policy</a>
                    <a href="#">Terms of Service</a>
                    <a href="#">Cookie Policy</a>
                </div>
            </div>
        </footer>

    </div>

    <script>
        // Sidebar Toggle
        const sidebar = document.getElementById('sidebar');
        const mainWrapper = document.getElementById('mainWrapper');
        const toggleBtn = document.getElementById('sidebarToggle');

        toggleBtn.addEventListener('click', () => {
            sidebar.classList.toggle('collapsed');
            mainWrapper.classList.toggle('sidebar-collapsed');
        });
    </script>
</body>
</html>
