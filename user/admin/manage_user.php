<?php
require_once __DIR__ . '/../../config/config.php';
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 5) {
    header("Location: ../../public/login/logAdmin.php?role=5&error=" . urlencode("Akses ditolak. Silakan login sebagai Admin."));
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Pengguna - HCTS Admin Center</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@500;700;800&family=Poppins:wght@400;500;600;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="../../style/manage_user.css?v=<?= time() ?>">
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
            <a href="akademik_admin.php" class="sidebar-link">
                <i class="fa-solid fa-book"></i>
                <span>Akademik</span>
            </a>
            <a href="taiwan.php" class="sidebar-link">
                <i class="fa-solid fa-globe"></i>
                <span>Program Taiwan</span>
            </a>
            <a href="manajemen_form.php" class="sidebar-link">
                <i class="fa-solid fa-file-pen"></i>
                <span>Manajemen Form</span>
            </a>
            <a href="manage_user.php" class="sidebar-link active">
                <i class="fa-solid fa-users-gear"></i>
                <span>Manajemen Pengguna</span>
            </a>
            <a href="#" class="sidebar-link">
                <i class="fa-solid fa-gear"></i>
                <span>Pengaturan</span>
            </a>
        </nav>
        <div class="sidebar-footer">
            <a href="../../app/logout.php" class="sidebar-link sidebar-logout" id="btnLogout">
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
                    <div class="admin-avatar" id="navAvatar">AD</div>
                    <div class="admin-info">
                        <span class="admin-name" id="navName">Admin</span>
                        <span class="admin-role" id="navRole">Super Admin</span>
                    </div>
                </div>
            </div>
        </header>

        <!-- ===== PAGE CONTENT ===== -->
        <main class="page-content">

            <!-- Hero / Dashboard Banner -->
            <section class="dashboard-banner">
                <div class="banner-content">
                    <h1 class="banner-title">Kelola Pengguna</h1>
                </div>
            </section>

            <!-- Stats Section (diisi via JS) -->
            <section class="stats-section" id="statsSection">
                <div class="stat-card stat-card-total">
                    <div class="stat-info">
                        <p class="stat-number" id="statTotal">—</p>
                        <p class="stat-label">Total Pengguna</p>
                    </div>
                    <div class="stat-icon"><i class="fa-solid fa-user-group"></i></div>
                </div>
                <div class="stat-card">
                    <p class="stat-number" id="statPimpinan">—</p>
                    <p class="stat-label">Pimpinan Aktif</p>
                </div>
                <div class="stat-card">
                    <p class="stat-number" id="statAdmin">—</p>
                    <p class="stat-label">Admin Aktif</p>
                </div>
                <div class="stat-card">
                    <p class="stat-number" id="statPengajar">—</p>
                    <p class="stat-label">Instruktur Aktif</p>
                </div>
                <div class="stat-card">
                    <p class="stat-number" id="statSiswa">—</p>
                    <p class="stat-label">Siswa Aktif</p>
                </div>
            </section>

            <!-- User List Section -->
            <section class="user-list-section">
                <div class="user-list-card">
                    <div class="table-header">
                        <h2 class="table-title">Daftar Pengguna</h2>
                        <div class="filter-controls">
                            <div class="search-box">
                                <i class="fa-solid fa-magnifying-glass"></i>
                                <input type="text" id="searchInput" placeholder="Cari Username / Email">
                                <button style="color:#888;" id="clearSearch"><i class="fa-solid fa-xmark"></i></button>
                            </div>
                            <select class="filter-select" id="filterRole">
                                <option value="">Role</option>
                                <option value="1">Siswa</option>
                                <option value="2">Calon Siswa</option>
                                <option value="3">Pengajar</option>
                                <option value="4">Pimpinan</option>
                                <option value="5">Admin</option>
                            </select>
                            <select class="filter-select" id="filterStatus">
                                <option value="">Status Akun</option>
                                <option value="1">Aktif</option>
                                <option value="0">Nonaktif</option>
                            </select>
                            <button class="btn-primary" onclick="openModal('modalInputData')">
                                <i class="fa-solid fa-plus"></i> Tambah Pengguna
                            </button>
                        </div>
                    </div>

                    <!-- Loading indicator -->
                    <div id="tableLoading" style="text-align:center;padding:32px;display:none;">
                        <i class="fa-solid fa-spinner fa-spin fa-2x" style="color:#c9a84c;"></i>
                        <p style="margin-top:8px;color:#888;">Memuat data...</p>
                    </div>

                    <!-- Alert box (notifikasi aksi) -->
                    <div id="alertBox" style="display:none;margin:0 0 12px;padding:12px 16px;border-radius:8px;font-size:14px;"></div>

                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>USERNAME / NAMA</th>
                                    <th>EMAIL</th>
                                    <th>ROLE</th>
                                    <th>STATUS AKUN</th>
                                    <th>AKSI</th>
                                </tr>
                            </thead>
                            <tbody id="userTableBody">
                                <tr><td colspan="5" style="text-align:center;color:#888;padding:32px;">Memuat data pengguna...</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>

        </main>



    </div>

    <!-- Modal: Tambah Pengguna Baru -->
    <div class="modal-overlay" id="modalInputData">
        <div class="modal-content" id="modalInputDataContent">
            <div class="modal-header">
                <h2 class="modal-title">Input Data Pengguna Baru</h2>
                <button class="modal-close" onclick="closeModal('modalInputData')"><i class="fa-solid fa-xmark"></i></button>
            </div>
            <div class="modal-body">
                <div id="createErrorBox" style="display:none;background:#fef2f2;border:1px solid #fca5a5;color:#991b1b;padding:10px 14px;border-radius:8px;font-size:13px;margin-bottom:14px;"></div>
                <form id="createUserForm" onsubmit="submitCreateUser(event)">
                    <div class="form-group">
                        <label>NIP <span style="color:red">*</span></label>
                        <input type="text" id="createUsername" class="form-control" placeholder="Masukkan NIP" required>
                    </div>
                    <div class="form-group">
                        <label>Nama Lengkap <span style="color:red">*</span></label>
                        <input type="text" id="createNama" class="form-control" placeholder="Masukkan Nama Lengkap" required>
                    </div>
                    <div class="form-group">
                        <label>Tanggal Lahir <span style="color:red">*</span></label>
                        <input type="date" id="createTglLahir" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Alamat <span style="color:red">*</span></label>
                        <input type="text" id="createAlamat" class="form-control" placeholder="Masukkan Alamat Lengkap" required>
                    </div>
                    <div class="form-group">
                        <label>Nomor WA <span style="color:red">*</span></label>
                        <input type="text" id="createNoWA" class="form-control" placeholder="Contoh: 08123456789" required>
                    </div>
                    <div class="form-group">
                        <label>Role Pengguna <span style="color:red">*</span></label>
                        <select id="createRole" class="form-control" required onchange="toggleSpesialisasi()">
                            <option value="">— Pilih Role —</option>
                            <option value="3">Pengajar</option>
                            <option value="4">Pimpinan</option>
                            <option value="5">Admin</option>
                        </select>
                    </div>
                    <div class="form-group" id="formSpesialisasi" style="display:none;">
                        <label>Spesialisasi <span style="color:red">*</span></label>
                        <input type="text" id="createSpesialisasi" class="form-control" placeholder="Masukkan Spesialisasi">
                    </div>
                    <button type="submit" class="btn-gold-full" id="createSubmitBtn">
                        <i class="fa-solid fa-plus"></i> Simpan Data Pengguna Baru
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal: Detail & Edit Akun -->
    <div class="modal-overlay" id="modalDetailAkun">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title">Detail Akun</h2>
                <button class="modal-close" onclick="closeModal('modalDetailAkun')"><i class="fa-solid fa-xmark"></i></button>
            </div>
            <div id="detailErrorBox" style="display:none;background:#fef2f2;border:1px solid #fca5a5;color:#991b1b;padding:10px 14px;border-radius:8px;font-size:13px;margin:0 24px 0;"></div>
            <div class="modal-body detail-grid">
                <div class="left-col" style="display:flex;flex-direction:column;gap:16px;">
                    <div class="info-card">
                        <h3 class="info-card-title">Informasi Pengguna</h3>
                        <div class="info-list">
                            <div><span>Username:</span> <strong id="detailUsernameText">—</strong></div>
                            <div><span>Email:</span> <strong id="detailEmail">—</strong></div>
                            <div><span>Status Akun:</span> <strong id="detailStatus">—</strong></div>
                            <div><span>Role:</span> <strong id="detailRole">—</strong></div>
                            <div><span>Tanggal Dibuat:</span> <strong id="detailCreatedAt">—</strong></div>
                        </div>
                    </div>
                    <div class="info-card">
                        <h3 class="info-card-title">Manajemen Akun</h3>
                        <input type="hidden" id="editUserId">
                        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:16px;">
                            <span style="font-size:13px;color:#555;">Ubah Role:</span>
                            <select id="editRole" class="form-control" style="width:auto;min-width:140px;">
                                <option value="1">Siswa</option>
                                <option value="2">Calon Siswa</option>
                                <option value="3">Pengajar</option>
                                <option value="4">Pimpinan</option>
                                <option value="5">Admin</option>
                            </select>
                        </div>
                        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:16px;">
                            <span style="font-size:13px;color:#555;">Status Akun:</span>
                            <label class="toggle-switch">
                                <input type="checkbox" id="editIsActive">
                                <span class="slider"></span>
                                <span class="status-label" id="editStatusLabel" style="margin-left:8px;">Aktif</span>
                            </label>
                        </div>
                        <div style="margin-bottom:16px;">
                            <span style="font-size:13px;color:#555;display:block;margin-bottom:6px;">Reset Password (opsional):</span>
                            <input type="password" id="editPassword" class="form-control" placeholder="Kosongkan jika tidak diubah">
                        </div>
                    </div>
                </div>
                <div class="right-col">
                    <div class="info-card" style="height:100%;">
                        <h3 class="info-card-title">Email & Identitas</h3>
                        <div style="margin-bottom:12px;">
                            <span style="font-size:13px;color:#555;display:block;margin-bottom:6px;">Ubah Username:</span>
                            <input type="text" id="editUsername" class="form-control" placeholder="Username">
                        </div>
                        <div style="margin-bottom:12px;">
                            <span style="font-size:13px;color:#555;display:block;margin-bottom:6px;">Ubah Email:</span>
                            <input type="email" id="editEmailInput" class="form-control" placeholder="Email pengguna">
                        </div>
                        <hr style="border:none;border-top:1px solid #eee;margin:16px 0;">
                        <h3 class="info-card-title">Info Sistem</h3>
                        <div style="font-size:13px;color:#666;line-height:1.8;">
                            <div>ID Pengguna: <strong id="detailId">—</strong></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-actions">
                <button class="btn-danger-light" id="btnDeactivate" onclick="confirmDeactivate()">
                    <i class="fa-solid fa-trash"></i> Hapus Akun
                </button>
                <button class="btn-gold" onclick="submitUpdateUser()">
                    <i class="fa-solid fa-floppy-disk"></i> Simpan Perubahan
                </button>
            </div>
        </div>
    </div>

    <!-- Modal Konfirmasi Hapus -->
    <div class="modal-overlay" id="modalConfirmDeactivate">
        <div class="modal-content" style="max-width:420px;">
            <div class="modal-header">
                <h2 class="modal-title">Konfirmasi Hapus Akun</h2>
                <button class="modal-close" onclick="closeModal('modalConfirmDeactivate')"><i class="fa-solid fa-xmark"></i></button>
            </div>
            <div class="modal-body" style="text-align:center;padding:24px;">
                <i class="fa-solid fa-triangle-exclamation fa-3x" style="color:#f59e0b;margin-bottom:16px;"></i>
                <p style="font-size:15px;color:#333;margin-bottom:8px;">Anda akan menghapus akun:</p>
                <p style="font-size:16px;font-weight:700;color:#1e3a5f;" id="confirmDeactivateName">—</p>
                <p style="font-size:13px;color:#888;margin-top:8px;">Data terkait pengguna ini akan dihapus secara permanen.</p>
            </div>
            <div class="modal-actions">
                <button class="btn-outline" onclick="closeModal('modalConfirmDeactivate')">Batal</button>
                <button class="btn-danger-light" id="confirmDeactivateBtn" onclick="executeDeactivate()">
                    <i class="fa-solid fa-trash"></i> Ya, Hapus Akun
                </button>
            </div>
        </div>
    </div>

    <script>
    const API_BASE = '../../backend/manage_user_end.php';

    async function apiCall(url, method = 'GET', body = null) {
        const opts = {
            method,
            headers: { 'Content-Type': 'application/json' },
        };
        if (body) opts.body = JSON.stringify(body);
        const res  = await fetch(url, opts);
        const data = await res.json();
        return { ok: res.ok, status: res.status, data };
    }

    function showAlert(msg, type = 'success') {
        const box = document.getElementById('alertBox');
        box.style.display = 'block';
        box.style.background = type === 'success' ? '#f0fdf4' : '#fef2f2';
        box.style.border     = '1px solid ' + (type === 'success' ? '#86efac' : '#fca5a5');
        box.style.color      = type === 'success' ? '#166534' : '#991b1b';
        box.innerHTML = (type === 'success' ? '✅ ' : '❌ ') + msg;
        setTimeout(() => { box.style.display = 'none'; }, 10000);
    }

    async function loadStats() {
        const { ok, data } = await apiCall(API_BASE + '?action=stats');
        if (!ok) return;
        const stats = data.data;
        document.getElementById('statTotal').textContent = stats.total ?? '—';
        const roleMap = { pimpinan: 'statPimpinan', admin: 'statAdmin', pengajar: 'statPengajar', siswa: 'statSiswa' };
        Object.keys(roleMap).forEach(key => {
            const found = (stats.per_role || []).find(r => r.role_name === key);
            document.getElementById(roleMap[key]).textContent = found ? found.jumlah : 0;
        });
    }

    async function loadUsers() {
        const roleId   = document.getElementById('filterRole').value;
        const isActive = document.getElementById('filterStatus').value;
        const search   = document.getElementById('searchInput').value;

        let url = API_BASE + '?action=list';
        if (roleId)   url += '&role_id=' + roleId;
        if (isActive !== '') url += '&is_active=' + isActive;
        if (search)   url += '&search=' + encodeURIComponent(search);

        document.getElementById('tableLoading').style.display = 'block';
        const { ok, data } = await apiCall(url);
        document.getElementById('tableLoading').style.display = 'none';

        if (!ok) {
            document.getElementById('userTableBody').innerHTML = '<tr><td colspan="5" style="text-align:center;">Gagal memuat data.</td></tr>';
            return;
        }

        const users = data.data || [];
        if (users.length === 0) {
            document.getElementById('userTableBody').innerHTML = '<tr><td colspan="5" style="text-align:center;">Tidak ada pengguna ditemukan.</td></tr>';
            return;
        }

        const badge = (roleName) => {
            const map = { siswa: 'badge-role-siswa', pengajar: 'badge-role-instruktur', admin: 'badge-role-admin', pimpinan: 'badge-role-pimpinan' };
            const cls = map[roleName] || 'badge-role-siswa';
            return `<span class="badge ${cls}">${roleName.replace('_',' ')}</span>`;
        };

        document.getElementById('userTableBody').innerHTML = users.map(u => `
            <tr>
                <td>${escHtml(u.username)}</td>
                <td>${escHtml(u.email)}</td>
                <td>${badge(u.role_name)}</td>
                <td><span class="badge ${u.is_active == 1 ? 'badge-status-aktif' : 'badge-status-nonaktif'}">${u.is_active == 1 ? 'Aktif' : 'Nonaktif'}</span></td>
                <td><button class="btn-detail" onclick='openDetailModal(${JSON.stringify(u)})'>Detail</button></td>
            </tr>
        `).join('');
    }

    function escHtml(str) {
        return String(str ?? '').replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
    }

    function openDetailModal(u) {
        document.getElementById('editUserId').value = u.id_user;
        document.getElementById('detailUsernameText').textContent = u.username;
        document.getElementById('detailEmail').textContent = u.email;
        document.getElementById('detailRole').textContent = u.role_name?.replace('_',' ');
        document.getElementById('detailStatus').textContent = u.is_active == 1 ? 'Aktif' : 'Nonaktif';
        document.getElementById('detailCreatedAt').textContent = u.create_at || '—';
        document.getElementById('detailId').textContent = u.id_user;
        document.getElementById('editUsername').value = u.username;
        document.getElementById('editEmailInput').value = u.email;
        document.getElementById('editRole').value = u.role_id;
        document.getElementById('editPassword').value = '';
        document.getElementById('editIsActive').checked = (u.is_active == 1);
        document.getElementById('editStatusLabel').textContent = u.is_active == 1 ? 'Aktif' : 'Nonaktif';
        openModal('modalDetailAkun');
    }

    function toggleSpesialisasi() {
        const roleId = document.getElementById('createRole').value;
        const formSpesialisasi = document.getElementById('formSpesialisasi');
        const inputSpesialisasi = document.getElementById('createSpesialisasi');
        if (roleId === '3') {
            formSpesialisasi.style.display = 'block';
            inputSpesialisasi.required = true;
        } else {
            formSpesialisasi.style.display = 'none';
            inputSpesialisasi.required = false;
        }
    }

    async function submitCreateUser(e) {
        e.preventDefault();
        const btn = document.getElementById('createSubmitBtn');
        btn.disabled = true;
        const payload = {
            action: 'add',
            username: document.getElementById('createUsername').value.trim(),
            nama: document.getElementById('createNama').value.trim(),
            tgl_lahir: document.getElementById('createTglLahir').value,
            alamat: document.getElementById('createAlamat').value.trim(),
            no_wa: document.getElementById('createNoWA').value.trim(),
            role_id: parseInt(document.getElementById('createRole').value),
        };
        if (payload.role_id === 3) {
            payload.spesialisasi = document.getElementById('createSpesialisasi').value.trim();
        }
        const { ok, data } = await apiCall(API_BASE, 'POST', payload);
        btn.disabled = false;
        if (!ok) { showAlert(data.message || 'Gagal menambah user', 'error'); return; }
        closeModal('modalInputData');
        document.getElementById('createUserForm').reset();
        document.getElementById('formSpesialisasi').style.display = 'none';
        
        let msg = 'Pengguna berhasil ditambahkan!';
        if (data.email && data.password) {
            msg += `<br>Email: <b>${data.email}</b><br>Password: <b>${data.password}</b>`;
        }
        showAlert(msg);
        loadUsers(); loadStats();
    }

    async function submitUpdateUser() {
        const payload = {
            action: 'edit',
            id_user: parseInt(document.getElementById('editUserId').value),
            username: document.getElementById('editUsername').value.trim(),
            email: document.getElementById('editEmailInput').value.trim(),
            role_id: parseInt(document.getElementById('editRole').value),
            is_active: document.getElementById('editIsActive').checked ? 1 : 0,
        };
        const pwd = document.getElementById('editPassword').value;
        if (pwd) payload.password = pwd;
        const { ok, data } = await apiCall(API_BASE, 'POST', payload);
        if (!ok) { showAlert(data.message || 'Gagal update user', 'error'); return; }
        closeModal('modalDetailAkun');
        showAlert('Data pengguna berhasil diperbarui!');
        loadUsers(); loadStats();
    }

    function confirmDeactivate() {
        document.getElementById('confirmDeactivateName').textContent = document.getElementById('detailUsernameText').textContent;
        openModal('modalConfirmDeactivate');
    }

    async function executeDeactivate() {
        const id = parseInt(document.getElementById('editUserId').value);
        const { ok, data } = await apiCall(API_BASE, 'POST', { action: 'delete', id_user: id });
        if (!ok) { showAlert(data.message || 'Gagal hapus user', 'error'); return; }
        closeModal('modalConfirmDeactivate'); closeModal('modalDetailAkun');
        showAlert('Pengguna berhasil dihapus.');
        loadUsers(); loadStats();
    }

    function openModal(id)  { document.getElementById(id).classList.add('active'); }
    function closeModal(id) { document.getElementById(id).classList.remove('active'); }

    document.getElementById('editIsActive').addEventListener('change', e => {
        document.getElementById('editStatusLabel').textContent = e.target.checked ? 'Aktif' : 'Nonaktif';
    });

    document.getElementById('sidebarToggle').addEventListener('click', () => {
        document.getElementById('sidebar').classList.toggle('collapsed');
        document.getElementById('mainWrapper').classList.toggle('sidebar-collapsed');
    });

    let searchTimeout;
    document.getElementById('searchInput').addEventListener('input', () => {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(loadUsers, 400);
    });
    document.getElementById('filterRole').addEventListener('change', loadUsers);
    document.getElementById('filterStatus').addEventListener('change', loadUsers);
    document.getElementById('clearSearch').addEventListener('click', () => {
        document.getElementById('searchInput').value = '';
        loadUsers();
    });

    (function init() {
        loadStats();
        loadUsers();
    })();
    </script>
</body>
</html>