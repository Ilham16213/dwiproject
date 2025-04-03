<?php
header('Content-Type: application/json');

// Contoh data; Anda bisa mengganti ini dengan data dinamis dari database atau sumber lain
$data = [
    'labels' => ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'],
    'datasets' => [
        [
            'label' => 'keseluruhan',
            'data' => [15, 20, 25, 130, 22, 18, 18],
            'backgroundColor' => 'rgba(21, 163, 106, 0.2)', // #15a36
            'borderColor' => 'rgba(21, 163, 106, 1)', // #15a36
            'borderWidth' => 1
        ],
        [
            'label' => 'Layak',
            'data' => [10, 15, 20, 25, 18, 12, 18],
            'backgroundColor' => 'rgba(54, 162, 235, 0.2)', // Warna untuk "Tidak Layak"
            'borderColor' => ' rgba(54, 162, 235, 1)', // Warna untuk "Tidak Layak"
            'borderWidth' => 1
        ],
        [
            'label' => 'Tidak Layak',
            'data' => [10, 15, 15, 25, 18, 10, 18],
            'backgroundColor' => 'rgba(255, 99, 132, 0.2)', // Warna untuk "Tidak Layak"
            'borderColor' => 'rgba(255, 99, 132, 1)', // Warna untuk "Tidak Layak"
            'borderWidth' => 1
        ]
    ]
];

echo json_encode($data);
?>
