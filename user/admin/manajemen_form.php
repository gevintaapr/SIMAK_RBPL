<?php
session_start();
require_once __DIR__ . '/../../config/config.php';

// Auth: hanya admin (role_id = 5)
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 5) {
    header("Location: ../../public/login/logAdmin.php?role=5&error=" . urlencode("Akses ditolak. Silakan login sebagai Admin."));
    exit;
}

// Fetch Templates
$query = mysqli_query($conn, "SELECT * FROM templates");
$templates = [];
while ($row = mysqli_fetch_assoc($query)) {
    $row['fields'] = json_decode($row['fields'], true);
    $templates[$row['code']] = $row;
}

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Form - HCTS Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@500;700;800&family=Poppins:wght@400;500;600;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="../../style/sertifikat_admin.css?v=<?= time() ?>">
    <link rel="stylesheet" href="../../style/popup_logout.css">
    <style>
        .form-container { max-width: 1000px; margin: 0 auto; padding: 20px 0; }
        .form-card { background: white; border-radius: 20px; padding: 30px; margin-bottom: 25px; border: 1px solid #eef2f6; box-shadow: 0 4px 15px rgba(0,0,0,0.02); }
        .form-header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 20px; }
        .form-title h2 { font-family: 'Playfair Display', serif; font-size: 20px; color: #003B73; margin: 0; }
        .form-title p { font-size: 13px; color: #64748b; margin-top: 5px; }
        .badge-active { background: #dcfce7; color: #166534; padding: 5px 12px; border-radius: 20px; font-size: 11px; font-weight: 700; }
        
        .form-info { display: flex; gap: 40px; margin-bottom: 20px; font-size: 13px; }
        .info-item .label { color: #94a3b8; display: block; margin-bottom: 3px; }
        .info-item .value { color: #1e293b; font-weight: 600; }
        
        .form-actions { display: flex; gap: 10px; border-top: 1px solid #f1f5f9; pt: 20px; margin-top: 20px; justify-content: flex-end; padding-top: 15px; }
        .btn-outline { border: 1px solid #e2e8f0; background: white; color: #475569; padding: 10px 20px; border-radius: 10px; font-weight: 600; font-size: 13px; transition: 0.3s; }
        .btn-outline:hover { background: #f8fafc; border-color: #cbd5e1; }
        .btn-edit-gold { background: #E9C46A; color: #003B73; border: none; padding: 10px 20px; border-radius: 10px; font-weight: 700; font-size: 13px; transition: 0.3s; }
        .btn-edit-gold:hover { background: #dfb34c; transform: translateY(-2px); }

        .tabs-container { margin-top: 40px; }
        .tabs-header { display: flex; gap: 30px; border-bottom: 2px solid #f1f5f9; margin-bottom: 30px; }
        .tab-btn { background: none; border: none; padding: 15px 0; font-size: 16px; font-weight: 700; color: #94a3b8; cursor: pointer; position: relative; font-family: 'Playfair Display', serif; }
        .tab-btn.active { color: #003B73; }
        .tab-btn.active::after { content: ''; position: absolute; bottom: -2px; left: 0; right: 0; height: 3px; background: #003B73; border-radius: 3px; }

        .preview-section { background: #F8FBFF; border-radius: 16px; padding: 25px; margin-bottom: 30px; }
        .preview-title { font-weight: 700; font-size: 15px; color: #003B73; margin-bottom: 20px; display: flex; justify-content: space-between; align-items: center; }
        .field-list { background: white; border-radius: 12px; border: 1px solid #e2e8f0; overflow: hidden; }
        .field-item { padding: 12px 20px; border-bottom: 1px solid #f1f5f9; display: flex; align-items: center; gap: 15px; font-size: 13px; color: #334155; }
        .field-item:last-child { border-bottom: none; }
        .field-item i { color: #94a3b8; }

        .modal-xl { width: 900px !important; }
        .edit-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 30px; }
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; font-size: 12px; font-weight: 700; color: #003B73; margin-bottom: 5px; }
        .form-group input, .form-group textarea { width: 100%; padding: 10px; border-radius: 8px; border: 1px solid #e2e8f0; font-family: 'Poppins', sans-serif; font-size: 13px; }
        .form-group textarea { height: 250px; resize: vertical; }

        .history-item { display: flex; gap: 20px; background: #EEF6FF; border-radius: 15px; padding: 20px; margin-bottom: 15px; border-left: 5px solid #003B73; }
        .history-v { width: 50px; height: 50px; background: #003B73; color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 14px; flex-shrink: 0; }
        .history-content h4 { margin: 0; font-size: 15px; color: #003B73; }
        .history-content p { margin: 5px 0 0; font-size: 12px; color: #64748b; }
    </style>
</head>
<body>
    <aside class="sidebar collapsed" id="sidebar">
        <div class="sidebar-header"><span class="sidebar-logo">HCTS</span></div>
        <nav class="sidebar-nav">
            <a href="dashboard_admin.php" class="sidebar-link"><i class="fa-solid fa-gauge-high"></i><span>Dashboard</span></a>
            <a href="pendaftaran_admin.php" class="sidebar-link"><i class="fa-solid fa-file-signature"></i><span>Pendaftaran</span></a>
            <a href="magang_admin.php" class="sidebar-link"><i class="fa-solid fa-briefcase"></i><span>Magang (OJT)</span></a>
            <a href="akademik_admin.php" class="sidebar-link"><i class="fa-solid fa-book"></i><span>Akademik</span></a>
            <a href="sertifikat_admin.php" class="sidebar-link"><i class="fa-solid fa-certificate"></i><span>Sertifikat</span></a>
            <a href="taiwan.php" class="sidebar-link"><i class="fa-solid fa-globe"></i><span>Program Taiwan</span></a>
            <a href="manajemen_form.php" class="sidebar-link active"><i class="fa-solid fa-file-pen"></i><span>Manajemen Form</span></a>
        </nav>
        <div class="sidebar-footer">
            <a href="#" onclick="showLogoutPopup(event)" class="sidebar-link sidebar-logout"><i class="fa-solid fa-right-from-bracket"></i><span>Logout</span></a>
        </div>
    </aside>

    <div class="main-wrapper sidebar-collapsed" id="mainWrapper">
        <header class="navbar">
            <div class="navbar-left">
                <button class="sidebar-toggle" id="sidebarToggle"><span></span><span></span><span></span></button>
                <span class="navbar-brand">HCTS Admin Center</span>
            </div>
            <div class="navbar-right">
                <div class="admin-profile"><div class="admin-avatar">AD</div><div class="admin-info"><span class="admin-name">Admin</span><span class="admin-role">Super Admin</span></div></div>
            </div>
        </header>

        <main class="page-content">
            <section class="dashboard-banner">
                <div class="form-container" style="padding:0;">
                    <div class="banner-content"><h1 class="banner-title">Manajemen Form</h1></div>
                </div>
            </section>

            <div class="form-container">
                <!-- Template 1 -->
                <div class="form-card">
                    <div class="form-header">
                        <div class="form-title">
                            <h2>Template Formulir Pendaftaran</h2>
                            <p>Formulir pendaftaran yang akan digunakan oleh seluruh calon siswa.</p>
                        </div>
                        <span class="badge-active">Aktif</span>
                    </div>
                    <div class="form-info">
                        <div class="info-item"><span class="label">Terakhir diperbarui</span><span class="value"><?= date('d F Y, H:i', strtotime($templates['surat_pernyataan']['updated_at'])) ?> WIB</span></div>
                        <div class="info-item"><span class="label">Digunakan oleh</span><span class="value">245 Calon Siswa</span></div>
                    </div>
                    <div class="form-actions">
                        <button class="btn-outline">Lihat Template</button>
                        <button class="btn-edit-gold" onclick='openEditModal("surat_pernyataan")'>Edit Template</button>
                    </div>
                </div>

                <!-- Template 2 -->
                <div class="form-card">
                    <div class="form-header">
                        <div class="form-title">
                            <h2>Template Laporan Kegiatan Harian</h2>
                            <p>Formulir yang akan didownload dan diisi siswa selama magang.</p>
                        </div>
                        <span class="badge-active">Aktif</span>
                    </div>
                    <div class="form-info">
                        <div class="info-item"><span class="label">Terakhir diperbarui</span><span class="value"><?= date('d F Y, H:i', strtotime($templates['laporan_harian']['updated_at'])) ?> WIB</span></div>
                        <div class="info-item"><span class="label">Digunakan oleh</span><span class="value">180 Siswa</span></div>
                    </div>
                    <div class="form-actions">
                        <button class="btn-outline">Lihat Template</button>
                        <button class="btn-edit-gold" onclick='openEditModal("laporan_harian")'>Edit Template</button>
                    </div>
                </div>

                <!-- Tabs -->
                <div class="tabs-container">
                    <div class="tabs-header">
                        <button class="tab-btn active" onclick="switchTab('struktur')">Struktur Template</button>
                        <button class="tab-btn" onclick="switchTab('riwayat')">Riwayat Perubahan</button>
                    </div>

                    <div id="tab-struktur" class="tab-content">
                        <div class="preview-section">
                            <div class="preview-title">
                                <span>Preview Struktur: <?= $templates['surat_pernyataan']['name'] ?></span>
                                <button class="btn-detail" style="padding: 5px 12px; font-size: 11px;" onclick='openEditModal("surat_pernyataan")'>Edit</button>
                            </div>
                            <div class="field-list">
                                <?php foreach ($templates['surat_pernyataan']['fields'] as $field): ?>
                                    <div class="field-item"><i class="fa-solid fa-grip-vertical"></i> <?= $field['label'] ?></div>
                                <?php endforeach; ?>
                            </div>
                        </div>

                        <div class="preview-section">
                            <div class="preview-title">
                                <span>Preview Struktur: <?= $templates['laporan_harian']['name'] ?></span>
                                <button class="btn-detail" style="padding: 5px 12px; font-size: 11px;" onclick='openEditModal("laporan_harian")'>Edit</button>
                            </div>
                            <div class="field-list">
                                <?php foreach ($templates['laporan_harian']['fields'] as $field): ?>
                                    <div class="field-item"><i class="fa-solid fa-grip-vertical"></i> <?= $field['label'] ?></div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>

                    <div id="tab-riwayat" class="tab-content" style="display: none;">
                        <div class="history-item">
                            <div class="history-v">V2.1</div>
                            <div class="history-content">
                                <h4>Diperbarui 13 Desember 2024</h4>
                                <p>Oleh: Admin | Template Form Pendaftaran</p>
                                <p style="color: #003B73; font-weight: 600; margin-top: 5px;">Penambahan field 'Asal Sekolah' di formulir</p>
                            </div>
                        </div>
                        <div class="history-item">
                            <div class="history-v">V1.1</div>
                            <div class="history-content">
                                <h4>Diperbarui 13 Desember 2024</h4>
                                <p>Oleh: Admin | Template Form Laporan Kegiatan Harian</p>
                                <p style="color: #003B73; font-weight: 600; margin-top: 5px;">Penambahan field 'Program' di formulir</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- Edit Modal -->
    <div class="modal-overlay" id="editModal">
        <div class="modal-content modal-xl">
            <div class="modal-header">
                <h2 class="modal-title" id="modalTitle">Edit Template</h2>
                <button class="modal-close" onclick="closeModal()"><i class="fa-solid fa-xmark"></i></button>
            </div>
            <div class="modal-divider"></div>
            <form id="templateForm">
                <input type="hidden" name="code" id="formCode">
                <div class="modal-body">
                    <div class="edit-grid">
                        <div class="edit-col">
                            <div class="form-group">
                                <label>Judul Dokumen</label>
                                <input type="text" name="title" id="formTitle" required>
                            </div>
                            <div class="form-group">
                                <label>Sub-Judul / Instansi</label>
                                <input type="text" name="subtitle" id="formSubtitle">
                            </div>
                            <div class="form-group">
                                <label>Konten Surat / Deskripsi</label>
                                <textarea name="content" id="formContent" required></textarea>
                                <p style="font-size: 10px; color: #64748b; margin-top: 5px;">Gunakan <b>[Daftar_Field]</b> untuk menyisipkan daftar input identitas.</p>
                            </div>
                        </div>
                        <div class="edit-col">
                            <label style="font-size: 12px; font-weight: 700; color: #003B73; display: block; margin-bottom: 15px;">Daftar Field Identitas</label>
                            <div id="fieldContainer">
                                <!-- Fields will be added here -->
                            </div>
                            <button type="button" class="btn-detail" style="width: 100%; margin-top: 10px;" onclick="addField()">+ Tambah Field Baru</button>
                        </div>
                    </div>
                </div>
                <div class="modal-divider"></div>
                <div class="modal-footer" style="padding: 20px; display: flex; justify-content: flex-end; gap: 10px;">
                    <button type="button" class="btn-outline" onclick="closeModal()">Batal</button>
                    <button type="submit" class="btn-primary-yellow">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        const templates = <?= json_encode($templates) ?>;

        document.getElementById('sidebarToggle').addEventListener('click', () => {
            document.getElementById('sidebar').classList.toggle('collapsed');
            document.getElementById('mainWrapper').classList.toggle('sidebar-collapsed');
        });

        function switchTab(tab) {
            document.querySelectorAll('.tab-btn').forEach(btn => btn.classList.remove('active'));
            document.querySelectorAll('.tab-content').forEach(c => c.style.display = 'none');
            event.target.classList.add('active');
            document.getElementById('tab-' + tab).style.display = 'block';
        }

        function openEditModal(code) {
            const data = templates[code];
            document.getElementById('formCode').value = code;
            document.getElementById('modalTitle').innerText = 'Edit ' + data.name;
            document.getElementById('formTitle').value = data.title;
            document.getElementById('formSubtitle').value = data.subtitle;
            document.getElementById('formContent').value = data.content;
            
            const fieldContainer = document.getElementById('fieldContainer');
            fieldContainer.innerHTML = '';
            data.fields.forEach((f, index) => addField(f.label, f.placeholder));
            
            document.getElementById('editModal').classList.add('show');
        }

        function addField(label = '', placeholder = '') {
            const div = document.createElement('div');
            div.className = 'form-group field-edit-item';
            div.style.background = '#f8fafc';
            div.style.padding = '10px';
            div.style.borderRadius = '8px';
            div.style.marginBottom = '10px';
            div.style.position = 'relative';
            div.innerHTML = `
                <div style="display:flex; gap:10px;">
                    <div style="flex:1">
                        <label>Label</label>
                        <input type="text" name="field_labels[]" value="${label}" placeholder="Ex: Nama Lengkap">
                    </div>
                    <div style="flex:1">
                        <label>Placeholder</label>
                        <input type="text" name="field_placeholders[]" value="${placeholder}" placeholder="Ex: [Nama]">
                    </div>
                </div>
                <button type="button" onclick="this.parentElement.remove()" style="position:absolute; top:5px; right:5px; border:none; background:none; color:#ef4444; cursor:pointer;"><i class="fa-solid fa-circle-xmark"></i></button>
            `;
            document.getElementById('fieldContainer').appendChild(div);
        }

        function closeModal() { document.getElementById('editModal').classList.remove('show'); }

        document.getElementById('templateForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            fetch('../../backend/saveTemplate.php', {
                method: 'POST',
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                alert(data.message);
                if(data.status === 'success') location.reload();
            });
        });

        function showLogoutPopup(e) { e.preventDefault(); document.getElementById('logoutPopup').style.display = 'flex'; }
        function closeLogoutPopup() { document.getElementById('logoutPopup').style.display = 'none'; }
    </script>

    <!-- Logout Popup -->
    <div id="logoutPopup" class="popup-overlay" style="display: none;">
        <div class="popup-wrapper">
            <div class="popup-content">
                <button class="btn-close-popup" onclick="closeLogoutPopup()">&times;</button>
                <div class="popup-body"><h3>Apakah Anda Yakin Ingin Keluar?</h3></div>
                <div class="popup-footer"><a href="../../app/logout.php" class="btn-yakin">Yakin</a><button class="btn-tidak" onclick="closeLogoutPopup()">Tidak</button></div>
            </div>
        </div>
    </div>
</body>
</html>
