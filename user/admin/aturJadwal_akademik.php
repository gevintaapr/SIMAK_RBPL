<?php
require_once __DIR__ . '/../../config/config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 5) {
    header("Location: ../../public/login/logAdmin.php?role=5&error=" . urlencode("Akses ditolak. Silakan login sebagai Admin."));
    exit;
}

// Fetch Schedules
$query = "SELECT j.*, u.username as nama_pengajar 
          FROM jadwal j 
          LEFT JOIN user u ON j.id_pengajar = u.id_user 
          ORDER BY FIELD(j.hari, 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'), j.jam_mulai ASC";
$res_jadwal = mysqli_query($conn, $query);
$jadwal_list = [];
if ($res_jadwal) {
    while ($row = mysqli_fetch_assoc($res_jadwal)) {
        $jadwal_list[] = $row;
    }
}

// Fetch Pengajar (Instructors)
$query_pengajar = "SELECT id_user, username FROM user WHERE role_id = 3 AND is_active = 1";
$res_pengajar = mysqli_query($conn, $query_pengajar);
$pengajar_list = [];
if ($res_pengajar) {
    while ($row = mysqli_fetch_assoc($res_pengajar)) {
        $pengajar_list[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Akademik - Atur Jadwal - HCTS</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@500;700;800&family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="../../style/aturJadwal_akademik.css?v=<?= time() ?>">
</head>
<body>

    <!-- ===== SIDEBAR ===== -->
    <aside class="sidebar collapsed" id="sidebar">
        <div class="sidebar-header">
            <span class="sidebar-logo">HCTS</span>
        </div>
        <nav class="sidebar-nav">
            <a href="dashboard_admin.php" class="sidebar-link">
                <i class="fa-solid fa-gauge-high"></i>
                <span>Dashboard</span>
            </a>
            <a href="pendaftaran_admin.php" class="sidebar-link">
                <i class="fa-solid fa-file-signature"></i>
                <span>Pendaftaran</span>
            </a>
            <a href="magang_admin.php" class="sidebar-link">
                <i class="fa-solid fa-briefcase"></i>
                <span>Magang (OJT)</span>
            </a>
            <a href="akademik_admin.php" class="sidebar-link active">
                <i class="fa-solid fa-book"></i>
                <span>Akademik</span>
            </a>
            <a href="#" class="sidebar-link">
                <i class="fa-solid fa-globe"></i>
                <span>Program Taiwan</span>
            </a>
            <a href="manage_user.php" class="sidebar-link">
                <i class="fa-solid fa-users-gear"></i>
                <span>Manajemen Pengguna</span>
            </a>
            <a href="#" class="sidebar-link">
                <i class="fa-solid fa-gear"></i>
                <span>Pengaturan</span>
            </a>
        </nav>
        <div class="sidebar-footer">
            <a href="../../app/logout.php" class="sidebar-link sidebar-logout">
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
                    <span></span><span></span><span></span>
                </button>
                <span class="navbar-brand">HCTS Admin Center</span>
            </div>
            <div class="navbar-right">
                <button class="notif-btn" id="notifBtn" aria-label="Notifikasi">
                    <i class="fa-regular fa-bell"></i>
                    <span class="notif-badge">5</span>
                </button>
                <div class="admin-profile">
                    <div class="admin-avatar">AD</div>
                    <div class="admin-info">
                        <span class="admin-name">Admin</span>
                        <span class="admin-role">Super Admin</span>
                    </div>
                    <i class="fa-solid fa-chevron-down admin-chevron"></i>
                </div>
            </div>
        </header>

        <!-- ===== PAGE CONTENT ===== -->
        <main class="page-content">

            <!-- Hero / Dashboard Banner -->
            <section class="dashboard-banner">
                <div class="banner-content">
                    <h1 class="banner-title">Akademik</h1>
                </div>
            </section>

            <!-- Tabs Navigation -->
            <div class="tabs-nav-container">
                <button class="tab-btn" onclick="window.location.href='akademik_admin.php'">
                    <i class="fa-solid fa-users"></i>
                    Daftar Siswa
                </button>
                <button class="tab-btn" onclick="window.location.href='verifPembayaran_akademik.php'">
                    <i class="fa-solid fa-wallet"></i>
                    Verifikasi Pembayaran
                </button>
                <button class="tab-btn active" onclick="window.location.href='aturJadwal_akademik.php'">
                    <i class="fa-solid fa-calendar-days"></i>
                    Atur Jadwal
                </button>
                <button class="tab-btn" onclick="window.location.href='verifEval_akademik.php'">
                    <i class="fa-solid fa-clipboard-check"></i>
                    Verifikasi Evaluasi
                </button>
            </div>

            <!-- Atur Jadwal Pembelajaran Section -->
            <section class="pendaftaran-list-section">
                <div class="pendaftaran-list-card">
                    <div class="table-header">
                        <h2 class="table-title">Atur Jadwal Pembelajaran</h2>
                    </div>
                    <div class="filter-controls">
                        <select class="filter-select" id="filterProgram">
                            <option value="">Program</option>
                            <option value="Hotel F&B Service">Hotel F&B Service</option>
                            <option value="Cruise Ship Deck Kadet">Cruise Ship Deck Kadet</option>
                            <option value="Cruise Ship Culinary">Cruise Ship Culinary</option>
                        </select>
                        <select class="filter-select" id="filterHari">
                            <option value="">Hari</option>
                            <option value="Senin">Senin</option>
                            <option value="Selasa">Selasa</option>
                            <option value="Rabu">Rabu</option>
                            <option value="Kamis">Kamis</option>
                            <option value="Jumat">Jumat</option>
                        </select>
                        
                        <button class="btn-tambah" onclick="openModal()">
                            <i class="fa-solid fa-plus"></i> Tambah Jadwal Baru
                        </button>
                    </div>

                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>HARI</th>
                                    <th>JAM</th>
                                    <th>MATERI</th>
                                    <th>PENGAJAR</th>
                                    <th>RUANGAN</th>
                                    <th>PROGRAM</th>
                                    <th>AKSI</th>
                                </tr>
                            </thead>
                            <tbody id="jadwalTableBody">
                                <?php if (empty($jadwal_list)): ?>
                                <tr>
                                    <td colspan="7" style="text-align: center; padding: 20px;">Belum ada jadwal yang diatur.</td>
                                </tr>
                                <?php else: ?>
                                    <?php foreach ($jadwal_list as $j): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($j['hari']) ?></td>
                                        <td><?= date('H:i', strtotime($j['jam_mulai'])) ?> - <?= date('H:i', strtotime($j['jam_selesai'])) ?></td>
                                        <td><?= htmlspecialchars($j['materi']) ?></td>
                                        <td><?= htmlspecialchars($j['nama_pengajar']) ?></td>
                                        <td><?= htmlspecialchars($j['ruangan']) ?></td>
                                        <td><?= htmlspecialchars($j['program']) ?></td>
                                        <td>
                                            <div class="action-buttons">
                                                <button class="btn-edit" onclick="editJadwal(<?= htmlspecialchars(json_encode($j)) ?>)">Edit</button>
                                                <button class="btn-hapus" onclick="deleteJadwal(<?= $j['id_jadwal'] ?>)">Hapus</button>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>

        </main>

        <!-- ===== FOOTER ===== -->
        <footer class="footer">
            <div class="footer-top">
                <div class="footer-col footer-brand-col">
                    <h3 class="footer-brand">HCTS</h3>
                    <p class="footer-desc">Sekolah pelatihan internasional terkemuka untuk karier di bidang perhotelan dan kapal pesiar.</p>
                </div>
                <div class="footer-col">
                    <h4 class="footer-heading">Program Kami</h4>
                    <ul class="footer-links">
                        <li><a href="#">Hotel Management</a></li>
                        <li><a href="#">Cruise Ship Operations</a></li>
                        <li><a href="#">Culinary Arts</a></li>
                    </ul>
                </div>
                <div class="footer-col">
                    <h4 class="footer-heading">Kontak Kami</h4>
                    <ul class="footer-contact">
                        <li><i class="fa-solid fa-location-dot"></i><span>123 Maritime Avenue, Harbor District, HD 12345</span></li>
                        <li><i class="fa-solid fa-phone"></i><span>+1 (555) 123-4567</span></li>
                        <li><i class="fa-regular fa-envelope"></i><span>info@hcts.edu</span></li>
                    </ul>
                </div>
            </div>
            <div class="footer-bottom">
                <p class="footer-copy">&copy; 2025 HCTS International. All rights reserved.</p>
            </div>
        </footer>

    </div>

    <!-- MODAL TAMBAH/EDIT JADWAL -->
    <div class="modal-overlay" id="jadwalModal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title" id="modalTitle">Input Jadwal Baru</h2>
                <button class="modal-close" onclick="closeModal()"><i class="fa-solid fa-xmark"></i></button>
            </div>
            <div class="modal-divider"></div>
            
            <form id="jadwalForm" onsubmit="saveJadwal(event)">
                <input type="hidden" name="id_jadwal" id="id_jadwal">
                <div class="modal-body">
                    <div class="form-group">
                        <label class="form-label">Program</label>
                        <select class="form-select" name="program" id="program" required>
                            <option value="" disabled selected>Pilih Program</option>
                            <option value="Hotel F&B Service">Hotel F&B Service</option>
                            <option value="Cruise Ship Deck Kadet">Cruise Ship Deck Kadet</option>
                            <option value="Cruise Ship Culinary">Cruise Ship Culinary</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Hari</label>
                        <select class="form-select" name="hari" id="hari" required>
                            <option value="" disabled selected>Pilih Hari</option>
                            <option value="Senin">Senin</option>
                            <option value="Selasa">Selasa</option>
                            <option value="Rabu">Rabu</option>
                            <option value="Kamis">Kamis</option>
                            <option value="Jumat">Jumat</option>
                            <option value="Sabtu">Sabtu</option>
                            <option value="Minggu">Minggu</option>
                        </select>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Jam Mulai</label>
                            <input type="time" class="form-input" name="jam_mulai" id="jam_mulai" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Jam Selesai</label>
                            <input type="time" class="form-input" name="jam_selesai" id="jam_selesai" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Materi Pembelajaran</label>
                        <input type="text" class="form-input" name="materi" id="materi" placeholder="E.g., Basic English" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Pengajar</label>
                        <select class="form-select" name="id_pengajar" id="id_pengajar" required>
                            <option value="" disabled selected>Pilih Pengajar</option>
                            <?php foreach ($pengajar_list as $p): ?>
                                <option value="<?= $p['id_user'] ?>"><?= htmlspecialchars($p['username']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Ruangan</label>
                        <select class="form-select" name="ruangan" id="ruangan" required>
                            <option value="" disabled selected>Pilih Ruangan</option>
                            <option value="Ruang A1">Ruang A1</option>
                            <option value="Ruang A2">Ruang A2</option>
                            <option value="Ruang B1">Ruang B1</option>
                            <option value="Ruang B2">Ruang B2</option>
                            <option value="Lab Restaurant">Lab Restaurant</option>
                            <option value="Lab Kitchen">Lab Kitchen</option>
                        </select>
                    </div>
                    
                    <button type="submit" class="btn-primary-yellow">Simpan Jadwal</button>
                </div>
            </form>
        </div>
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

        // Modal Logic
        const modal = document.getElementById('jadwalModal');
        const form = document.getElementById('jadwalForm');
        
        function openModal() {
            document.getElementById('modalTitle').textContent = 'Input Jadwal Baru';
            form.reset();
            document.getElementById('id_jadwal').value = '';
            modal.classList.add('show');
        }

        function closeModal() {
            modal.classList.remove('show');
        }

        function editJadwal(data) {
            document.getElementById('modalTitle').textContent = 'Edit Jadwal';
            document.getElementById('id_jadwal').value = data.id_jadwal;
            document.getElementById('program').value = data.program;
            document.getElementById('hari').value = data.hari;
            document.getElementById('jam_mulai').value = data.jam_mulai;
            document.getElementById('jam_selesai').value = data.jam_selesai;
            document.getElementById('materi').value = data.materi;
            document.getElementById('id_pengajar').value = data.id_pengajar;
            document.getElementById('ruangan').value = data.ruangan;
            modal.classList.add('show');
        }

        function saveJadwal(e) {
            e.preventDefault();
            const formData = new FormData(form);
            const id = document.getElementById('id_jadwal').value;
            formData.append('action', id ? 'edit' : 'add');

            fetch('../../backend/jadwal_end.php', {
                method: 'POST',
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                if (data.status === 'success') {
                    alert(data.message);
                    location.reload();
                } else {
                    alert('Gagal: ' + data.message);
                }
            })
            .catch(err => {
                console.error(err);
                alert('Terjadi kesalahan.');
            });
        }

        function deleteJadwal(id) {
            if (confirm('Apakah Anda yakin ingin menghapus jadwal ini?')) {
                const formData = new FormData();
                formData.append('action', 'delete');
                formData.append('id_jadwal', id);

                fetch('../../backend/jadwal_end.php', {
                    method: 'POST',
                    body: formData
                })
                .then(res => res.json())
                .then(data => {
                    if (data.status === 'success') {
                        alert(data.message);
                        location.reload();
                    } else {
                        alert('Gagal: ' + data.message);
                    }
                })
                .catch(err => {
                    console.error(err);
                    alert('Terjadi kesalahan.');
                });
            }
        }

        // Close on outside click
        modal.addEventListener('click', (e) => {
            if (e.target === modal) {
                closeModal();
            }
        });
    </script>
</body>
</html>