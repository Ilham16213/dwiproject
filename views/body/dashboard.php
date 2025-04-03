<?php
// Ambil data dari tabel data_alternatif berdasarkan tanggal_input
$sql = "SELECT * FROM `data_alternatif`;";
$result = $conn->query($sql);

$alternatives = [];
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $alternatives[] = $row;
    }
}

// Ambil data dari tabel data_bobot
$sql = "SELECT nama, bobot, status FROM data_bobot";
$result = $conn->query($sql);

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
$layak_count = 0;
$tidak_layak_count = 0;

foreach ($normalized_data as $row) {
    $score = 0;
    foreach ($weights as $criteria => $weight) {
        $score += $row[$criteria] * $weight;
    }

    // Hitung jumlah layak dan tidak layak
    if ($score >= 0.8) {
        $layak_count++;
    } else {
        $tidak_layak_count++;
    }

    $final_scores[] = [
        'id_alternatif' => $row['id_alternatif'],
        'nama_alternatif' => $row['nama_alternatif'],
        'score' => $score
    ];
}

?>

<h1 class="app-page-title">Beranda</h1>
				<div class="app-card alert alert-dismissible shadow-sm mb-4 border-left-decoration" role="alert" style="background-color: #ffffff;">
					<div class="inner">
						<div class="app-card-body p-3 p-lg-4">
							<h3 class="mb-3">
								<?php echo strtoupper('Selamat Datang ' . $_SESSION['username']); ?>
							</h3>

							<div class="row gx-5 gy-3">
								<div class="col-12 col-lg-12">
									
                                    
								<?php if ($_SESSION['akses']  === 'manajer') { ?> 
										<div>Web ini digunakan untuk mengelola data dan menentukan kelayakan tanaman. Anda sebagai manajer berkontribusi untuk memberikan nilai bobot/kriteria dari tanaman</div>
									<?php } else if ($_SESSION['akses']  === 'owner') { ?> 
										<div>Web ini digunakan untuk mengelola data dan menentukan kelayakan tanaman. Anda sebagai owner dapat melihat kinerja pekerja anda</div>
									<?php } else { ?>
										<div>Web ini digunakan untuk mengelola data dan menentukan kelayakan tanaman. Anda sebagai staff dapat memasukan data tanaman</div>
									<?php } ?>
								</div><!--//col-->
							</div><!--//row-->
							<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
						</div><!--//app-card-body-->
					</div><!--//inner-->
				</div>

				    
			    <div class="row g-4 mb-4">
				    <div class="col-8 col-lg-4">
					    <div class="app-card app-card-stat shadow-sm h-100">
						    <div class="app-card-body p-3 p-lg-4">
							    <h4 class="stats-type mb-1">Data Tanaman</h4>
								<?php 

									$sql = "SELECT COUNT(*) AS jumlah_data FROM data_alternatif";
									$result = $conn->query($sql);
									// Memeriksa hasil query
									if ($result->num_rows > 0) {
										// Menampilkan jumlah data
										$row = $result->fetch_assoc();
										echo "<div class='stats-figure'>" . $row['jumlah_data'] . "</div>";
									}

								?>
							 
						    </div><!--//app-card-body-->
					    </div><!--//app-card-->
				    </div><!--//col-->

				    <div class="col-8 col-lg-4">
					    <div class="app-card app-card-stat shadow-sm h-100">
						    <div class="app-card-body p-3 p-lg-4">
							    <h4 class="stats-type mb-1">Tanaman Layak</h4>
							    <div class="stats-figure">
									<?php echo $layak_count; ?> 
								</div>
						    </div><!--//app-card-body-->
					    </div><!--//app-card-->
				    </div><!--//col-->

				    <div class="col-8 col-lg-4">
					    <div class="app-card app-card-stat shadow-sm h-100">
						    <div class="app-card-body p-3 p-lg-4">
							    <h4 class="stats-type mb-1">Tanaman Tidak Layak</h4>
							    <div class="stats-figure">
									<?php echo $tidak_layak_count; ?> 
								</div>
						    </div><!--//app-card-body-->
					    </div><!--//app-card-->
				    </div><!--//col-->
			    </div><!--//row-->
			    <div class="row g-4 mb-4">
				<div class="col-12 col-lg-6">
				        <div class="app-card app-card-progress-list h-100 shadow-sm">
					        <div class="app-card-header p-3">
						        <div class="row justify-content-between align-items-center">
							        <div class="col-auto">
						                <h4 class="app-card-title">Statistik Bobot</h4>
							        </div><!--//col-->
						        </div><!--//row-->
					        </div><!--//app-card-header-->
					        <div class="app-card-body">
							    <div class="item p-3">
								    <div class="row align-items-center">
									    <div class="col">
										    <div class="title mb-1 ">(C1) KESEHATAN TANAMAN</div>
											<?php
												// Query untuk mengambil data bobot
												$sqlc1 = "SELECT bobot FROM data_bobot WHERE nama = '(C1) KESEHATAN TANAMAN'";
												$resultc1 = $conn->query($sqlc1);

												while ($rowc1 = $resultc1->fetch_assoc()) {
													$bobotc1 = $rowc1['bobot'];
													$persentasic1 = ($bobotc1 / 1) * 100;
												}

											?>
										    <div class="progress">
  <div class="progress-bar bg-success" role="progressbar" style="width: <?php echo $persentasic1;?>%;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
</div>

									    </div><!--//col-->
								    </div><!--//row-->
							    </div><!--//item-->
							    
							    
							     <div class="item p-3">
								    <div class="row align-items-center">
									    <div class="col">
										    <div class="title mb-1 ">(C2) PERTUMBUHAN TANAMAN</div>
											
											<?php
												// Query untuk mengambil data bobot
												$sqlc2 = "SELECT bobot FROM data_bobot WHERE nama = '(C2) PERTUMBUHAN TANAMAN'";
												$resultc2 = $conn->query($sqlc2);

												while ($rowc2 = $resultc2->fetch_assoc()) {
													$bobotc2 = $rowc2['bobot'];
													$persentasic2 = ($bobotc2 / 1) * 100;
												}

											?>
										    <div class="progress">
  <div class="progress-bar bg-success" role="progressbar" style="width: <?php echo $persentasic2;?>%;" aria-valuenow="34" aria-valuemin="0" aria-valuemax="100"></div>
</div>
									    </div><!--//col-->
								    </div><!--//row-->
							    </div><!--//item-->
							    
							    <div class="item p-3">
								    <div class="row align-items-center">
									    <div class="col">
										    <div class="title mb-1 ">(C3) WARNA DAUN</div>
											
											<?php
												// Query untuk mengambil data bobot
												$sqlc3 = "SELECT bobot FROM data_bobot WHERE nama = '(C3) WARNA DAUN'";
												$resultc3 = $conn->query($sqlc3);

												while ($rowc3 = $resultc3->fetch_assoc()) {
													$bobotc3 = $rowc3['bobot'];
													$persentasic3 = ($bobotc3 / 1) * 100;
												}

											?>
										    <div class="progress">
  <div class="progress-bar bg-success" role="progressbar" style="width: <?php echo $persentasic3;?>%;" aria-valuenow="68" aria-valuemin="0" aria-valuemax="100"></div>
</div>
									    </div><!--//col-->
								    </div><!--//row-->
							    </div><!--//item-->
							    
							    <div class="item p-3">
								    <div class="row align-items-center">
									    <div class="col">
										    <div class="title mb-1 ">(C4) KUALITAS AKAR</div>
											<?php
												// Query untuk mengambil data bobot
												$sqlc4 = "SELECT bobot FROM data_bobot WHERE nama = '(C4) KUALITAS AKAR'";
												$resultc4 = $conn->query($sqlc4);

												while ($rowc4 = $resultc4->fetch_assoc()) {
													$bobotc4 = $rowc4['bobot'];
													$persentasic4 = ($bobotc4 / 1) * 100;
												}

											?>
										    <div class="progress">
  <div class="progress-bar bg-success" role="progressbar" style="width: <?php echo $persentasic4;?>%;" aria-valuenow="52" aria-valuemin="0" aria-valuemax="100"></div>
</div>
									    </div><!--//col-->
								    </div><!--//row-->
							    </div><!--//item-->

								
							    <div class="item p-3">
								    <div class="row align-items-center">
									    <div class="col">
										    <div class="title mb-1 ">KESELURUHAN (100%)</div>
											<?php
												// Query untuk mengambil data bobot
												$sqlc4 = "SELECT bobot FROM data_bobot WHERE nama = '(C4) KUALITAS AKAR'";
												$resultc4 = $conn->query($sqlc4);

												while ($rowc4 = $resultc4->fetch_assoc()) {
													$bobotc4 = $rowc4['bobot'];
													$persentasic4 = ($bobotc4 / 1) * 100;
												}

											?>
											<div class="progress">
												<div class="progress-bar bg-secondary" role="progressbar" style="width: <?php echo $persentasic1;?>%;" aria-valuenow="30" aria-valuemin="0" aria-valuemax="100"></div>
												<div class="progress-bar bg-primary" role="progressbar" style="width: <?php echo $persentasic2;?>%;" aria-valuenow="30" aria-valuemin="0" aria-valuemax="100"></div>
												<div class="progress-bar bg-warning" role="progressbar" style="width: <?php echo $persentasic3;?>%;" aria-valuenow="30" aria-valuemin="0" aria-valuemax="100"></div>
												<div class="progress-bar bg-danger" role="progressbar" style="width: <?php echo $persentasic4;?>%;" aria-valuenow="10" aria-valuemin="0" aria-valuemax="100"></div>
											</div>
									    </div><!--//col-->
								    </div><!--//row-->
							    </div><!--//item-->
		
					        </div><!--//app-card-body-->
				        </div><!--//app-card-->
			        </div><!--//col-->
					<div class="col-12 col-lg-6">
						<div class="app-card app-card-chart h-100 shadow-sm">
							<div class="app-card-header p-3">
								<div class="row justify-content-between align-items-center">
									<div class="col-auto">
										<h4 class="app-card-title">Data tanaman yang dimasukan selama seminggu</h4>
									</div><!--//col-->
								</div><!--//row-->
							</div><!--//app-card-header-->
							<div class="app-card-body p-3 p-lg-4">
								<div class="chart-container">
									<canvas id="canvas"></canvas>
								</div>
							</div><!--//app-card-body-->
						</div><!--//app-card-->
					</div><!--//col-->


				
			    </div><!--//row-->


				<?php
// Koneksi ke database
$koneksi = new mysqli('localhost', 'root', '', 'bangdwi');

// Cek apakah koneksi berhasil
if ($koneksi->connect_error) {
    die("Koneksi gagal: " . $koneksi->connect_error);
}

// Inisialisasi 7 variabel untuk jumlah data per hari
$hari1 = 0;
$hari2 = 0;
$hari3 = 0;
$hari4 = 0;
$hari5 = 0;
$hari6 = 0;
$hari7 = 0;

// Array untuk menyimpan data hasil query
$data = [
    date('Y-m-d', strtotime('-6 days')) => 0,
    date('Y-m-d', strtotime('-5 days')) => 0,
    date('Y-m-d', strtotime('-4 days')) => 0,
    date('Y-m-d', strtotime('-3 days')) => 0,
    date('Y-m-d', strtotime('-2 days')) => 0,
    date('Y-m-d', strtotime('-1 days')) => 0,
    date('Y-m-d') => 0,
];

// Query ke database
$query = "
    SELECT 
        DATE(tanggal_input) as tanggal,
        COUNT(*) as jumlah
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

// Cek apakah query berhasil
if ($result) {
    // Masukkan hasil query ke array
    while ($row = $result->fetch_assoc()) {
        $data[$row['tanggal']] = (int)$row['jumlah'];
    }
} else {
    echo "Error: " . $koneksi->error;
}

// Assign data ke masing-masing variabel
$hari1 = $data[date('Y-m-d', strtotime('-6 days'))];
$hari2 = $data[date('Y-m-d', strtotime('-5 days'))];
$hari3 = $data[date('Y-m-d', strtotime('-4 days'))];
$hari4 = $data[date('Y-m-d', strtotime('-3 days'))];
$hari5 = $data[date('Y-m-d', strtotime('-2 days'))];
$hari6 = $data[date('Y-m-d', strtotime('-1 days'))];
$hari7 = $data[date('Y-m-d')];

// Tutup koneksi
$koneksi->close();
?>
				
<script>
// Data dari PHP
const hari1 = <?php echo json_encode($hari1); ?>;
const hari2 = <?php echo json_encode($hari2); ?>;
const hari3 = <?php echo json_encode($hari3); ?>;
const hari4 = <?php echo json_encode($hari4); ?>;
const hari5 = <?php echo json_encode($hari5); ?>;
const hari6 = <?php echo json_encode($hari6); ?>;
const hari7 = <?php echo json_encode($hari7); ?>;

// Label tanggal (7 hari terakhir)
const labels = [
    '<?php echo date('Y-m-d', strtotime('-6 days')); ?>',
    '<?php echo date('Y-m-d', strtotime('-5 days')); ?>',
    '<?php echo date('Y-m-d', strtotime('-4 days')); ?>',
    '<?php echo date('Y-m-d', strtotime('-3 days')); ?>',
    '<?php echo date('Y-m-d', strtotime('-2 days')); ?>',
    '<?php echo date('Y-m-d', strtotime('-1 days')); ?>',
    '<?php echo date('Y-m-d'); ?>'
];

// Data untuk Chart
const data = {
    labels: labels,
    datasets: [{
        label: 'Jumlah Data per Hari',
        data: [hari1, hari2, hari3, hari4, hari5, hari6, hari7],
        backgroundColor: 'rgba(21, 163, 98, 0.6)', // Warna hijau dengan transparansi
        borderColor: '#15a362', // Warna hijau solid
        borderWidth: 2,
        tension: 0.4, // Membuat garis sedikit melengkung
        fill: true
    }]
};

// Konfigurasi Chart
const config = {
    type: 'line', // Gunakan 'bar' untuk chart batang atau 'line' untuk chart garis
    data: data,
    options: {
        responsive: true,
        plugins: {
            legend: {
                display: true,
                position: 'top'
            }
        },
        scales: {
            x: {
                title: {
                    display: true,
                    text: 'Tanggal'
                }
            },
            y: {
                beginAtZero: true,
                title: {
                    display: true,
                    text: 'Jumlah Data'
                }
            }
        }
    }
};

// Render Chart
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('canvas').getContext('2d');
    new Chart(ctx, config);
});
</script>
