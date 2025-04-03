<?php
$tcpdf_path = __DIR__ . '/../../../assets/tcpdf/tcpdf.php';

// Include file tcpdf.php
if (file_exists($tcpdf_path)) {
    include_once $tcpdf_path;
} else {
    die("File tcpdf.php tidak ditemukan.");
}

// Koneksi ke database
$mysqli = new mysqli("localhost", "root", "", "bangdwi");

if ($mysqli->connect_error) {
    die("Koneksi gagal: " . $mysqli->connect_error);
}

// Ambil parameter tanggal dari URL
$daritanggal = isset($_GET['daritanggal']) ? $_GET['daritanggal'] : date('Y-m-d');
$sampaitanggal = isset($_GET['sampaitanggal']) ? $_GET['sampaitanggal'] : date('Y-m-d');

$daritanggalconvert = DateTime::createFromFormat('Y-m-d', $daritanggal);
$sampaitanggalconvert = DateTime::createFromFormat('Y-m-d', $sampaitanggal);

// Mengubah format tanggal menjadi DD-MM-YYYY
$formatted_date_dari = $daritanggalconvert->format('d-m-Y');
$formatted_date_sampai = $sampaitanggalconvert->format('d-m-Y');

// Ambil data dari tabel data_alternatif berdasarkan tanggal_input
$sql = "SELECT * 
FROM `data_alternatif` 
WHERE DATE(tanggal_input) BETWEEN '$daritanggal' AND '$sampaitanggal';";


$sql = "SELECT * FROM `data_alternatif` WHERE DATE(tanggal_input) BETWEEN '$daritanggal' AND '$sampaitanggal' ORDER BY CAST(SUBSTRING(nama_alternatif, 2) AS UNSIGNED) ASC;";
$result = $mysqli->query($sql);

$alternatives = [];
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $alternatives[] = $row;
    }
}


// Ambil data dari tabel data_bobot
$sql = "SELECT nama, bobot, status FROM data_bobot";
$result = $mysqli->query($sql);

// Inisialisasi array untuk tipe kriteria dan bobot
$criteria_types = [];
$weights = [];

// Isi array dengan data dari database
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $criteria_key = strtolower(substr($row['nama'], 1, 2)); // Mengambil c1, c2, dst dari nama kolom
        $criteria_types[$criteria_key] = $row['status'];
        $weights[$criteria_key] = $row['bobot'];
    }
}
// Normalisasi data
$normalized_data = [];
foreach ($alternatives as $alternative) {
    $normalized_row = ['id_alternatif' => $alternative['id_alternatif'], 'nama_alternatif' => $alternative['nama_alternatif']];
    
    foreach ($criteria_types as $criteria => $type) {
        $values = array_column($alternatives, $criteria);
        $max_value = max($values);
        $min_value = min($values);
        
        if ($type === 'benefit') {
            $normalized_row[$criteria] = $alternative[$criteria] / $max_value;
        } else {
            $normalized_row[$criteria] = $min_value / $alternative[$criteria];
        }
    }
    
    $normalized_data[] = $normalized_row;
}

// Hitung nilai akhir SAW dan perhitungan nilai per kriteria
$final_scores = [];
foreach ($normalized_data as $row) {
    $score = 0;
    foreach ($weights as $criteria => $weight) {
        $score += $row[$criteria] * $weight;
    }
    $final_scores[] = [
        'id_alternatif' => $row['id_alternatif'],
        'nama_alternatif' => $row['nama_alternatif'],
        'score' => $score
    ];
}


// Inisialisasi TCPDF
$pdf = new TCPDF('L', 'mm', 'A4', true, 'UTF-8', false);

// Setel metadata dokumen
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Your Name');
$pdf->SetTitle('Rekap Hasil Uji Kelayakan Tanaman');
$pdf->SetSubject('PDF Rekap');

// Setel margin
$pdf->SetMargins(10, 10, 10);
$pdf->SetAutoPageBreak(TRUE, 10);

// Tambah halaman baru
$pdf->AddPage();

// Setel font dan judul
$pdf->SetFont('helvetica', 'B', 14);
$pdf->Cell(0, 10, 'DATA KELAYAKAN EKSPOR TANAMAN', 0, 1, 'C');
$pdf->SetFont('helvetica', '', 12);
$pdf->Cell(0, 10, 'PERIODE UJI: ' . $formatted_date_dari . ' Sampai ' . $formatted_date_sampai , 0, 1, 'C');
$pdf->Ln(5);

// Header tabel
$pdf->SetFont('helvetica', 'B', 10);
$pdf->SetFillColor(255, 255, 255); // Warna latar belakang putih
$pdf->SetTextColor(0, 0, 0);       // Warna teks hitam
$pdf->SetDrawColor(0, 0, 0);       // Warna garis hitam

$header = ['NO.', 'KODE', 'KESEHATAN TANAMAN', 'PERTUMBUHAN TANAMAN', 'WARNA DAUN', 'KUALITAS AKAR', 'NILAI UJI', 'HASIL UJI'];
$w = [10, 20, 50, 50, 50, 50, 20, 30];
$num_headers = count($header);

// Gambar header tabel
for ($i = 0; $i < $num_headers; ++$i) {
    $pdf->MultiCell($w[$i], 7, $header[$i], 1, 'C', 1, 0, '', '', true, 0, false, true, 7, 'M', true);
}
$pdf->Ln();
// Data Tabel
$pdf->SetFont('helvetica', '', 7);

$no = 1;
$line_count = 0; // Inisialisasi counter baris

foreach ($final_scores as $row) {
    if ($line_count > 0 && $line_count % 22 == 0) {
        // Jika sudah 22 baris, tambahkan halaman baru dan gambar ulang header
        $pdf->AddPage();
        
        // Gambar ulang header tabel di halaman baru
        for ($i = 0; $i < $num_headers; ++$i) {
            $pdf->MultiCell($w[$i], 7, $header[$i], 1, 'C', 1, 0, '', '', true, 0, false, true, 7, 'M', true);
        }
        $pdf->Ln();
    }
    
    $pdf->MultiCell($w[0], 7, $no++, 1, 'C', 0, 0);
    $pdf->MultiCell($w[1], 7, $row['nama_alternatif'], 1, 'C', 0, 0);
    
    $alternatif = $row['nama_alternatif'];
    $sql1 = "SELECT * FROM data_alternatif WHERE nama_alternatif = '$alternatif'";

    $result1 = $mysqli->query($sql1);
    
    if ($result1 && $result1->num_rows > 0) {
        $c = [
            'c1' => '-',  // Default nilai
            'c2' => '-',  // Default nilai
            'c3' => '-',  // Default nilai
            'c4' => '-'   // Default nilai
        ];
        
        while ($row1 = $result1->fetch_assoc()) {
            $c1 = $row1['c1'];
            $c2 = $row1['c2'];
            $c3 = $row1['c3'];
            $c4 = $row1['c4'];
            
            $sql2 = "SELECT 
                        data_kriteria.nama AS nama_kriteria,
                        data_bobot.nama AS nama_bobot
                    FROM 
                        data_kriteria
                    INNER JOIN 
                        data_bobot 
                    ON 
                        data_kriteria.id_bobot = data_bobot.id_bobot
                    WHERE 
                        (data_bobot.nama = '(C1) KESEHATAN TANAMAN' AND data_kriteria.nilai = $c1)
                        OR (data_bobot.nama = '(C2) PERTUMBUHAN TANAMAN' AND data_kriteria.nilai = $c2)
                        OR (data_bobot.nama = '(C3) WARNA DAUN' AND data_kriteria.nilai = $c3)
                        OR (data_bobot.nama = '(C4) KUALITAS AKAR' AND data_kriteria.nilai = $c4)";
            
            $result2 = $mysqli->query($sql2);
            
            if ($result2 && $result2->num_rows > 0) {
                while ($row2 = $result2->fetch_assoc()) {
                    switch ($row2['nama_bobot']) {
                        case '(C1) KESEHATAN TANAMAN':
                            $c['c1'] = $row2['nama_kriteria'];
                            break;
                        case '(C2) PERTUMBUHAN TANAMAN':
                            $c['c2'] = $row2['nama_kriteria'];
                            break;
                        case '(C3) WARNA DAUN':
                            $c['c3'] = $row2['nama_kriteria'];
                            break;
                        case '(C4) KUALITAS AKAR':
                            $c['c4'] = $row2['nama_kriteria'];
                            break;
                    }
                }
            }
        }
        
        $pdf->MultiCell($w[2], 7, $c['c1'], 1, 'L', 0, 0);
        $pdf->MultiCell($w[3], 7, $c['c2'], 1, 'L', 0, 0);
        $pdf->MultiCell($w[4], 7, $c['c3'], 1, 'L', 0, 0);
        $pdf->MultiCell($w[5], 7, $c['c4'], 1, 'L', 0, 0);
        $pdf->MultiCell($w[6], 7, number_format($row['score'], 4), 1, 'C', 0, 0);
        $pdf->MultiCell($w[7], 7, $row['score'] >= 0.8 ? 'Layak' : 'Tidak Layak', 1, 'C', 0, 0);
        $pdf->Ln();
        
        $line_count++; // Tambah counter baris
    }
}


// Tutup koneksi
$mysqli->close();

// Output PDF
$pdf->Output('rekap_hasil_uji.pdf', 'I');
?>
