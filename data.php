<?php
header('Content-Type: application/json');
// Koneksi ke database
$koneksi = new mysqli('localhost', 'root', '', 'bangdwi');

// Inisialisasi array data berdasarkan hari dalam seminggu
$labels = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];
$keseluruhanData = [0, 0, 0, 0, 0, 0, 0];
$layakData = [0, 0, 0, 0, 0, 0, 0];
$tidakLayakData = [0, 0, 0, 0, 0, 0, 0];

// Query untuk mendapatkan data selama 7 hari terakhir
$query = "
    SELECT 
        DATE(tanggal_input) as tanggal,
        COUNT(*) as jumlah,
        SUM(CASE WHEN status = 'Layak' THEN 1 ELSE 0 END) as layak,
        SUM(CASE WHEN status = 'Tidak Layak' THEN 1 ELSE 0 END) as tidak_layak
    FROM 
        data_alternatif
    WHERE 
        tanggal_input >= DATE_SUB(CURDATE(), INTERVAL 6 DAY)
    GROUP BY 
        DATE(tanggal_input)
    ORDER BY 
        tanggal_input ASC
";
$result = $koneksi->query($query);

// Menyimpan data berdasarkan hari
while ($row = $result->fetch_assoc()) {
    $index = date('N', strtotime($row['tanggal'])) - 1; // Mendapatkan indeks hari (Senin = 0, Minggu = 6)
    
    // Isi data sesuai dengan indeks hari
    $keseluruhanData[$index] = $row['jumlah'];
    $layakData[$index] = $row['layak'];
    $tidakLayakData[$index] = $row['tidak_layak'];
}

// Membuat struktur data untuk Chart.js
$data = [
    'labels' => $labels,
    'datasets' => [
        [
            'label' => 'Keseluruhan',
            'data' => $keseluruhanData,
            'backgroundColor' => 'rgba(21, 163, 106, 0.2)', // #15a36
            'borderColor' => 'rgba(21, 163, 106, 1)', // #15a36
            'borderWidth' => 1
        ],
        [
            'label' => 'Layak',
            'data' => $layakData,
            'backgroundColor' => 'rgba(54, 162, 235, 0.2)', // Warna untuk "Layak"
            'borderColor' => 'rgba(54, 162, 235, 1)', // Warna untuk "Layak"
            'borderWidth' => 1
        ],
        [
            'label' => 'Tidak Layak',
            'data' => $tidakLayakData,
            'backgroundColor' => 'rgba(255, 99, 132, 0.2)', // Warna untuk "Tidak Layak"
            'borderColor' => 'rgba(255, 99, 132, 1)', // Warna untuk "Tidak Layak"
            'borderWidth' => 1
        ]
    ]
];

// Tampilkan data dalam bentuk JSON (untuk kebutuhan Chart.js)
echo json_encode($data);

?>
