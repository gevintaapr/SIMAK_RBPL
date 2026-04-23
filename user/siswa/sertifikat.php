<?php
session_start();
require_once __DIR__ . '/../../config/config.php';

// Proteksi Halaman
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 1) {
    header("Location: ../../public/login/logSiswa.php?role=1&error=" . urlencode("Akses ditolak. Silakan login terlebih dahulu."));
    exit;
}

$id_user = $_SESSION['user_id'];

// Ambil Data Siswa Lengkap (Join dengan pendaftaran untuk mendapatkan tanggal_lahir)
$query_siswa = mysqli_query($conn, "
    SELECT s.*, p.tanggal_lahir, p.alamat 
    FROM siswa s 
    LEFT JOIN pendaftaran p ON s.id_pendaftaran = p.id_pendaftaran 
    WHERE s.id_user = $id_user
");
$siswa = mysqli_fetch_assoc($query_siswa);

if (!$siswa) {
    echo "Profil siswa tidak ditemukan.";
    exit;
}

$id_siswa = $siswa['id_siswa'];

// Ambil Data Evaluasi (Nilai)
$query_eval = mysqli_query($conn, "SELECT * FROM evaluasi WHERE id_siswa = $id_siswa LIMIT 1");
$eval = mysqli_fetch_assoc($query_eval);

// Jika belum ada nilai, arahkan kembali atau beri peringatan
if (!$eval) {
    echo "<script>alert('Sertifikat belum tersedia. Nilai evaluasi Anda belum diinput oleh pengajar.'); window.location.href='dashboard_siswa.php';</script>";
    exit;
}

// Mapel Mapping
$mapel_names = [
    'DUI1' => 'English for Hospitality',
    'DUI2' => 'Hotel & Cruise Ship Overview',
    'DUI3' => 'Food & Beverage Service Foundation',
    'DUI4' => 'Kitchen & Food Production Basics',
    'DUI5' => 'Housekeeping & Laundry Fundamentals',
    'DUI6' => 'Front Office & Guest Interaction',
    'DUI7' => 'Basic Safety Training (BST) & STCW',
    'DUI8' => 'Grooming & Professional Conduct',
];

function getGrade($score) {
    if ($score >= 90) return 'A';
    if ($score >= 85) return 'A-';
    if ($score >= 80) return 'B+';
    if ($score >= 75) return 'B';
    if ($score >= 70) return 'C';
    return 'D';
}

$grades = [];
$no = 1;
foreach ($mapel_names as $code => $name) {
    $point = $eval[$code] ?? 0;
    $grades[] = [
        'no' => $no++,
        'subject' => $name,
        'point' => (int)$point,
        'grade' => getGrade($point)
    ];
}

$dob_indonesia = "-";
if (!empty($siswa['tanggal_lahir'])) {
    $months = [
        'January' => 'JANUARI', 'February' => 'FEBRUARI', 'March' => 'MARET',
        'April' => 'APRIL', 'May' => 'MEI', 'June' => 'JUNI',
        'July' => 'JULI', 'August' => 'AGUSTUS', 'September' => 'SEPTEMBER',
        'October' => 'OKTOBER', 'November' => 'NOVEMBER', 'December' => 'DESEMBER'
    ];
    $dob_raw = date('d F Y', strtotime($siswa['tanggal_lahir']));
    $dob_parts = explode(' ', $dob_raw);
    $month_name = $dob_parts[1] ?? '';
    $dob_parts[1] = $months[$month_name] ?? $month_name;
    $dob_indonesia = implode(' ', $dob_parts);
}

$studentData = [
    'name' => strtoupper($siswa['nama_lengkap']),
    'nim' => $siswa['nim_siswa'] ?? '-',
    'pob' => 'INDONESIA', 
    'dob' => $dob_indonesia,
    'program' => strtoupper($siswa['program_pembelajaran'] ?? 'HOTEL AND CRUISE SHIP'),
    'completionDate' => date('M jS Y'),
    'director' => 'Agus Handoyo, SE',
    'grades' => $grades,
    'average' => number_format($eval['rata_rata'], 1) . ' (' . getGrade($eval['rata_rata']) . ')'
];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Sertifikat - <?= htmlspecialchars($siswa['nama_lengkap']) ?></title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/react@18/umd/react.production.min.js"></script>
    <script src="https://unpkg.com/react-dom@18/umd/react-dom.production.min.js"></script>
    <script src="https://unpkg.com/@babel/standalone/babel.min.js"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    
    <link href="https://fonts.googleapis.com/css2?family=Great+Vibes&family=Inter:wght@400;500;600;700;800;900&family=Playfair+Display:ital,wght@0,600;0,700;1,600&display=swap" rel="stylesheet">
    
    <style>
        body { font-family: 'Inter', sans-serif; margin: 0; padding: 0; }
        @media print {
            body { background: white !important; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
            .certificate-page { margin: 0 !important; border: none !important; box-shadow: none !important; width: 210mm !important; height: 297mm !important; page-break-after: always; position: relative; }
            .print:hidden { display: none !important; }
            #root { padding: 0 !important; }
        }
        .certificate-page { box-sizing: border-box; }
    </style>
</head>
<body class="bg-slate-200">
    <div id="root">
        <div class="flex items-center justify-center min-h-screen">
            <div class="text-slate-500 font-medium italic">Menyiapkan Sertifikat...</div>
        </div>
    </div>

    <script type="text/babel">
        const { useState, useEffect } = React;

        const LogoSVG = ({ className }) => (
            <svg viewBox="0 0 100 100" className={className}>
                <polygon points="50,10 90,50 50,90 10,50" fill="none" stroke="currentColor" strokeWidth="2" />
                <path d="M30,50 L50,30 L70,50 L50,70 Z" fill="currentColor" opacity="0.8" />
                <path d="M40,50 L50,40 L60,50 L50,60 Z" fill="currentColor" />
            </svg>
        );

        const CertificateApp = () => {
            const [currentPage, setCurrentPage] = useState(1);
            const studentData = <?= json_encode($studentData) ?>;

            useEffect(() => {
                if (window.lucide) {
                    window.lucide.createIcons();
                }
            }, [currentPage]);

            const handlePrint = () => { window.print(); };

            return (
                <div className="min-h-screen py-12 px-4 flex flex-col items-center">
                    <div className="mb-8 flex gap-4 print:hidden items-center bg-white p-5 rounded-2xl shadow-xl border border-gray-200 sticky top-4 z-50">
                        <button 
                            onClick={() => setCurrentPage(1)}
                            className={`px-5 py-2.5 rounded-xl flex items-center gap-2 font-semibold transition-all ${currentPage === 1 ? 'bg-indigo-700 text-white shadow-lg scale-105' : 'bg-gray-100 hover:bg-gray-200 text-gray-700'}`}
                        >
                            <i data-lucide="award" className="w-5 h-5"></i> Halaman Depan
                        </button>
                        <button 
                            onClick={() => setCurrentPage(2)}
                            className={`px-5 py-2.5 rounded-xl flex items-center gap-2 font-semibold transition-all ${currentPage === 2 ? 'bg-indigo-700 text-white shadow-lg scale-105' : 'bg-gray-100 hover:bg-gray-200 text-gray-700'}`}
                        >
                            <i data-lucide="check-circle" className="w-5 h-5"></i> Transkrip Nilai
                        </button>
                        <div className="h-8 w-[1px] bg-gray-300 mx-2"></div>
                        <button 
                            onClick={handlePrint}
                            className="px-6 py-2.5 bg-emerald-600 text-white rounded-xl flex items-center gap-2 hover:bg-emerald-700 transition-all shadow-lg active:scale-95"
                        >
                            <i data-lucide="printer" className="w-5 h-5"></i> Cetak PDF
                        </button>
                        <a href="dashboard_siswa.php" className="px-5 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl flex items-center gap-2 font-semibold transition-all">
                            <i data-lucide="home" className="w-5 h-5"></i> Dashboard
                        </a>
                    </div>

                    <div className="flex flex-col gap-12 items-center">
                        {(currentPage === 1 || window.matchMedia('print').matches) && (
                            <div className="certificate-page relative bg-[#fafafa] w-[210mm] h-[297mm] shadow-2xl p-10 overflow-hidden print:shadow-none print:m-0 border-[16px] border-double border-indigo-950">
                                <div className="absolute inset-0 flex items-center justify-center opacity-[0.03] pointer-events-none">
                                    <LogoSVG className="w-[500px] h-[500px] text-indigo-900" />
                                </div>
                                <div className="absolute top-0 left-0 w-64 h-64 bg-indigo-900 opacity-[0.07] rounded-br-full -translate-x-20 -translate-y-20"></div>
                                <div className="absolute bottom-0 right-0 w-80 h-80 bg-indigo-900 opacity-[0.07] rounded-tl-full translate-x-20 translate-y-20"></div>
                                <div className="relative z-10 flex flex-col h-full border-2 border-indigo-900/20 p-10">
                                    <div className="text-center mb-8">
                                        <div className="flex justify-center mb-4">
                                            <div className="w-28 h-28 bg-indigo-950 rounded-full flex items-center justify-center p-3 relative shadow-xl border-4 border-white">
                                                <LogoSVG className="w-full h-full text-white" />
                                            </div>
                                        </div>
                                        <div className="text-[11px] font-bold text-indigo-900/80 space-y-1">
                                            <p>IJIN DINSOSNAKERTRANS NO. 563/200/14</p>
                                            <p>IJIN DIKNAS NO. 437/2844/640/11</p>
                                        </div>
                                    </div>
                                    <div className="text-center mb-10">
                                        <h1 className="text-4xl font-black text-indigo-950 tracking-[0.2em]">HCTS INDONESIA</h1>
                                        <h2 className="text-xl italic text-indigo-900 mt-1">HOTEL AND CRUISE SHIP TRAINING SCHOOL</h2>
                                    </div>
                                    <div className="text-center mb-8">
                                        <div className="inline-block border-y-4 border-indigo-900 px-12 py-2">
                                            <h3 className="text-3xl font-extrabold text-gray-800 uppercase">CERTIFICATE OF COMPLETION</h3>
                                        </div>
                                        <p className="mt-6 text-gray-600 italic text-lg">This is proudly presented to</p>
                                    </div>
                                    <div className="text-center mb-12">
                                        <h4 className="text-6xl text-indigo-950 py-4" style={{ fontFamily: "'Great Vibes', cursive" }}>{studentData.name}</h4>
                                        <div className="w-2/3 h-[2px] bg-indigo-200 mx-auto -mt-2"></div>
                                    </div>
                                    <div className="max-w-lg mx-auto w-full space-y-5 mb-12 bg-white/40 p-6 rounded-lg border border-indigo-100 shadow-sm">
                                        <div className="flex justify-between items-start border-b border-indigo-100 pb-2">
                                            <div className="w-1/2 text-[12px] font-bold text-indigo-900">NOMOR INDUK MAHASISWA<br/><span className="text-[10px] italic text-gray-500 font-normal">Registration Number</span></div>
                                            <div className="w-1/2 font-bold text-gray-800">: {studentData.nim}</div>
                                        </div>
                                        <div className="flex justify-between items-start border-b border-indigo-100 pb-2">
                                            <div className="w-1/2 text-[12px] font-bold text-indigo-900">TEMPAT TANGGAL LAHIR<br/><span className="text-[10px] italic text-gray-500 font-normal">Place/Date of Birth</span></div>
                                            <div className="w-1/2 text-gray-800 font-semibold uppercase">: {studentData.pob}, {studentData.dob}</div>
                                        </div>
                                        <div className="flex justify-between items-start border-b border-indigo-100 pb-2">
                                            <div className="w-1/2 text-[12px] font-bold text-indigo-900">JURUSAN<br/><span className="text-[10px] italic text-gray-500 font-normal">PROGRAM</span></div>
                                            <div className="w-1/2 text-gray-800 font-semibold uppercase">: {studentData.program}</div>
                                        </div>
                                    </div>
                                    <div className="text-center px-16 mb-20 text-gray-700">
                                        <p>has successfully completed the program at Hotel and Cruise Ship Training School</p>
                                        <p className="font-bold italic mt-3 text-lg text-indigo-900">for 1 (one) year program</p>
                                    </div>
                                    <div className="mt-auto flex justify-between items-end px-12 pb-12">
                                        <div className="text-center w-52">
                                            <div className="h-24 flex items-center justify-center opacity-30"><LogoSVG className="w-16 h-16" /></div>
                                            <div className="border-t-2 border-indigo-950 pt-2 font-bold text-sm uppercase text-indigo-950">{studentData.name}</div>
                                        </div>
                                        <div className="text-center text-sm mb-24 italic font-semibold text-indigo-900">Klaten, {studentData.completionDate}</div>
                                        <div className="text-center w-52">
                                            <div className="h-24 flex items-center justify-center"><div className="w-16 h-16 border border-gray-300 bg-slate-100 flex items-center justify-center text-[8px] text-gray-400 uppercase">QR CODE</div></div>
                                            <div className="border-t-2 border-indigo-950 pt-2 font-bold text-sm uppercase text-indigo-950">{studentData.director}<br/><span className="text-xs text-gray-600 font-medium italic">Director</span></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        )}

                        {(currentPage === 2 || window.matchMedia('print').matches) && (
                            <div className="certificate-page relative bg-white w-[210mm] h-[297mm] shadow-2xl p-16 overflow-hidden print:shadow-none print:m-0 border-none">
                                <div className="text-center border-b-4 border-double border-gray-800 pb-6 mb-10">
                                    <h2 className="text-3xl font-black text-gray-800 tracking-[0.4em] uppercase">STUDENT ACHIEVEMENT</h2>
                                </div>
                                <div className="mb-10 space-y-3 text-sm uppercase font-bold text-gray-700">
                                    <div className="grid grid-cols-4"><span>NAME</span><span className="col-span-3">: {studentData.name}</span></div>
                                    <div className="grid grid-cols-4"><span>NIM</span><span className="col-span-3">: {studentData.nim}</span></div>
                                    <div className="grid grid-cols-4"><span>DEPARTMENT</span><span className="col-span-3">: {studentData.program.split(' / ')[0]}</span></div>
                                </div>
                                <div className="overflow-hidden rounded-lg border-2 border-gray-800">
                                    <table className="w-full border-collapse">
                                        <thead className="bg-gray-100 uppercase">
                                            <tr>
                                                <th className="border-b-2 border-r-2 border-gray-800 p-4 w-16">NO</th>
                                                <th className="border-b-2 border-r-2 border-gray-800 p-4 text-left">SUBJECT</th>
                                                <th className="border-b-2 border-r-2 border-gray-800 p-4 w-32">POINT</th>
                                                <th className="border-b-2 border-gray-800 p-4 w-32">GRADE</th>
                                            </tr>
                                        </thead>
                                        <tbody className="divide-y-2 divide-gray-800">
                                            {studentData.grades.map((item) => (
                                                <tr key={item.no} className={item.no % 2 === 0 ? 'bg-gray-50' : 'bg-white'}>
                                                    <td className="border-r-2 border-gray-800 p-3 text-center">{item.no}</td>
                                                    <td className="border-r-2 border-gray-800 p-3 pl-6 font-medium">{item.subject}</td>
                                                    <td className="border-r-2 border-gray-800 p-3 text-center font-bold text-indigo-900">{item.point}</td>
                                                    <td className="p-3 text-center font-black">{item.grade}</td>
                                                </tr>
                                            ))}
                                        </tbody>
                                    </table>
                                </div>
                                <div className="text-right mt-12 bg-indigo-50 p-6 rounded-xl border border-indigo-100 inline-block float-right min-w-[300px]">
                                    <p className="text-xl italic font-serif text-indigo-950">With the predicate achieved: <br/>
                                    <span className="font-black text-2xl not-italic underline decoration-4 decoration-indigo-300 ml-2">{studentData.average}</span></p>
                                </div>
                            </div>
                        )}
                    </div>
                </div>
            );
        };

        const root = ReactDOM.createRoot(document.getElementById('root'));
        root.render(<CertificateApp />);
    </script>
</body>
</html>
