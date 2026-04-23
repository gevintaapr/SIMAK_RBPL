<?php
session_start();
require_once __DIR__ . '/../../config/config.php';

if (!isset($_GET['code'])) {
    die("Invalid request");
}

$code = mysqli_real_escape_string($conn, $_GET['code']);
$query = mysqli_query($conn, "SELECT * FROM templates WHERE code = '$code'");
$template = mysqli_fetch_assoc($query);

if (!$template) {
    die("Template not found");
}

$fields = json_decode($template['fields'], true);
$field_html = "";

if ($code === 'surat_pernyataan') {
    foreach ($fields as $f) {
        $field_html .= "<div style='margin-bottom: 8px; display: flex;'>
                            <div style='width: 200px; font-weight: bold;'>{$f['label']}</div>
                            <div>: {$f['placeholder']}</div>
                        </div>";
    }
} else if ($code === 'laporan_harian') {
    foreach ($fields as $f) {
        $field_html .= "<div style='margin-bottom: 8px; display: flex;'>
                            <div style='width: 150px; font-weight: bold;'>{$f['label']}</div>
                            <div>: {$f['placeholder']}</div>
                        </div>";
    }
    // Add Daily Report Table
    $field_html .= "<table style='width: 100%; border-collapse: collapse; margin-top: 20px;'>
                        <thead>
                            <tr style='background: #f1f5f9;'>
                                <th style='border: 1px solid #cbd5e1; padding: 10px; width: 40px;'>No</th>
                                <th style='border: 1px solid #cbd5e1; padding: 10px;'>Nama Kegiatan / Aktivitas</th>
                                <th style='border: 1px solid #cbd5e1; padding: 10px; width: 150px;'>Paraf Supervisor</th>
                            </tr>
                        </thead>
                        <tbody>";
    for ($i = 1; $i <= 10; $i++) {
        $field_html .= "<tr>
                            <td style='border: 1px solid #cbd5e1; padding: 15px; text-align: center;'>$i</td>
                            <td style='border: 1px solid #cbd5e1; padding: 15px;'></td>
                            <td style='border: 1px solid #cbd5e1; padding: 15px;'></td>
                        </tr>";
    }
    $field_html .= "</tbody></table>";
}

$content = str_replace('[Daftar_Field]', $field_html, $template['content']);
if ($code === 'laporan_harian') {
    // Append content if it's not already using [Daftar_Field]
    if (strpos($template['content'], '[Daftar_Field]') === false) {
        $content = $template['content'] . "<br><br>" . $field_html;
    }
}

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Download <?= $template['name'] ?></title>
    <style>
        body { font-family: 'Times New Roman', Times, serif; padding: 50px; line-height: 1.6; color: #1e293b; max-width: 800px; margin: auto; }
        .header { text-align: center; margin-bottom: 40px; border-bottom: 3px double #334155; padding-bottom: 20px; }
        .header h1 { font-size: 18px; text-transform: uppercase; margin: 0; }
        .header p { font-size: 14px; margin: 5px 0 0; }
        .body-text { text-align: justify; white-space: pre-line; margin-bottom: 40px; }
        .signature-section { margin-top: 60px; display: flex; justify-content: space-between; }
        .signature-item { text-align: center; width: 250px; }
        .no-print { position: fixed; top: 20px; left: 20px; background: #003B73; color: white; padding: 10px 20px; border-radius: 8px; text-decoration: none; font-weight: bold; }
        @media print { .no-print { display: none; } body { padding: 0; } }
    </style>
</head>
<body onload="window.print()">
    <a href="javascript:window.close()" class="no-print">Close / Kembali</a>

    <div class="header">
        <h1><?= $template['title'] ?></h1>
        <p><?= $template['subtitle'] ?></p>
    </div>

    <div class="body-text">
        <?= $content ?>
    </div>

    <?php if($code === 'surat_pernyataan'): ?>
    <div class="signature-section">
        <div class="signature-item">
            <!-- Left side usually empty or for witness -->
        </div>
        <div class="signature-item">
            <p>[Kota], <?= date('d F Y') ?></p>
            <p>Yang Membuat Pernyataan,</p>
            <div style="height: 80px; margin: 10px 0; display: flex; align-items: center; justify-content: center; font-size: 10px; color: #94a3b8; border: 1px dashed #cbd5e1;">(Meterai 10.000 jika diperlukan)</div>
            <p style="font-weight: bold;">( ____________________________ )</p>
            <p style="font-size: 12px; margin-top: -5px;">Nama Terang & Tanda Tangan</p>
        </div>
    </div>
    <?php else: ?>
    <div class="signature-section">
        <div class="signature-item">
            <p>Mengetahui,</p>
            <p>Dosen Pembimbing / Supervisor</p>
            <div style="height: 80px;"></div>
            <p style="font-weight: bold;">( ____________________________ )</p>
        </div>
        <div class="signature-item">
            <p>[Kota], <?= date('d F Y') ?></p>
            <p>Mahasiswa / Siswa,</p>
            <div style="height: 80px;"></div>
            <p style="font-weight: bold;">( ____________________________ )</p>
        </div>
    </div>
    <?php endif; ?>

</body>
</html>
