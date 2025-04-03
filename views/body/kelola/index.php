<?php
require_once 'controller/transaksi_controller.php';

?>
<?php
// Query untuk mengecek data
$sql = "SELECT * FROM data_bobot";
$result = $conn->query($sql);

if ($result->num_rows < 1) {
    
    echo "<script>
            alert('Buat bobot terlebih dahulu');
            window.location.href = 'index.php?page=bobot';
        </script>";

}
?>
<h1 class="app-page-title">Transaksi</h1>
<?php
// Ambil data dari tabel data_alternatif
$sql = "SELECT * FROM data_alternatif";
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
    $normalized_row = ['id_alternatif' => $alternative['id_alternatif'], 'nama_alternatif' => $alternative['nama_alternatif'], 'tanggal_input' => date('d-m-Y H:i:s', strtotime($alternative['tanggal_input'])) ];
    
    foreach ($criteria_types as $criteria => $type) {
        if ($type === 'benefit') {
            $normalized_row[$criteria] = $alternative[$criteria] / max(array_column($alternatives, $criteria));
        } else {
            $normalized_row[$criteria] = min(array_column($alternatives, $criteria)) / $alternative[$criteria];
        }
    }
    
    $normalized_data[] = $normalized_row;
}

// Hitung nilai akhir SAW dan perhitungan nilai per kriteria
$final_scores = [];
$calculation_values = [];
foreach ($normalized_data as $row) {
    $score = 0;
    $calculation_row = ['id_alternatif' => $row['id_alternatif'], 'nama_alternatif' => $row['nama_alternatif'], 'tanggal_input' => $row['tanggal_input']];
    
    foreach ($weights as $criteria => $weight) {
        $calculation_row[$criteria] = $row[$criteria] * $weight;
        $score += $calculation_row[$criteria];
    }
    
    $calculation_values[] = $calculation_row;
    $final_scores[] = [
        'id_alternatif' => $row['id_alternatif'],
        'nama_alternatif' => $row['nama_alternatif'],
        'tanggal_input' => $row['tanggal_input'],
        'score' => $score
    ];
}

// Urutkan berdasarkan skor tertinggi
usort($final_scores, function($a, $b) {
    return $b['score'] <=> $a['score'];
});


?>


<?php if ($_SESSION['akses'] === 'staff' || $_SESSION['akses'] === 'manajer') { ?> 
<div class="row g-3 mb-4 align-items-center justify-content-between">
    <div class="col-auto">
        <div class="page-utilities">
            <div class="row g-2 justify-content-start justify-content-md-end align-items-center">
                <div class="col-auto">
								
                                <button type="submit" class="btn-sm app-btn-secondary form-control-sm"  data-toggle="modal" data-target="#myModal">Tambah Alternatif</button>
                        
                            
                </div>
                
                <?php if ($_SESSION['akses'] === 'manajer') { ?> 
                    <div class="col-auto">
                                                    
                        <?php
                        // Query untuk mengecek data
                        $sql = "SELECT * FROM data_alternatif";
                        $result = $conn->query($sql);

                        if ($result->num_rows > 0) {

                        echo "<a type='button' class='btn btn-danger btn-sm' href='index.php?page=transaksi&hapusall=" .  1  . "' onclick='return confirmDeleteAll()'>Hapus Semua Alternatif</a>";

                        }
                        ?>
                            
                                
                    </div>
                    
                <?php } ?> 
            </div>
        </div>
    </div>
</div>

<?php } ?> 

<?php
if(isset($_GET['insert'])) {
?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        Data Berhasil Dimasukan Ke Database.
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div> 
<?php
} else if(isset($_GET['delete'])) {
?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        Data Berhasil Dihapus Dari Database.
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div> 
<?php
} else if(isset($_GET['update'])) {
?>
    <div class="alert alert-primary alert-dismissible fade show" role="alert">
        Data Berhasil Diupdate Ke Database.
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div> 
<?php
} 
?>

			    
<nav id="orders-table-tab" class="orders-table-tab app-nav-tabs nav shadow-sm flex-column flex-sm-row mb-4">
				    <a class="flex-sm-fill text-sm-center nav-link active" id="orders-all-tab" data-bs-toggle="tab" href="#orders-all" role="tab" aria-controls="orders-all" aria-selected="true">Hasil akhir</a>
				    <a class="flex-sm-fill text-sm-center nav-link"  id="orders-paid-tab" data-bs-toggle="tab" href="#orders-paid" role="tab" aria-controls="orders-paid" aria-selected="false">Normalisasi</a>
				    <a class="flex-sm-fill text-sm-center nav-link" id="orders-pending-tab" data-bs-toggle="tab" href="#orders-pending" role="tab" aria-controls="orders-pending" aria-selected="false">Hitung nilai</a>
				    <a class="flex-sm-fill text-sm-center nav-link" id="orders-cancelled-tab" data-bs-toggle="tab" href="#orders-cancelled" role="tab" aria-controls="orders-cancelled" aria-selected="false">Semua</a>
				</nav>
				
				
				<div class="tab-content" id="orders-table-tab-content">
			        <div class="tab-pane fade show active" id="orders-all" role="tabpanel" aria-labelledby="orders-all-tab">              
                                <h4 style="color: #15a362; margin-bottom: 15px; border-bottom: 3px solid #15a362; padding-bottom: 5px; font-weight: bold;">Hasil Perhitungan Nilai SAW</h4>
                                <div class="table-responsive">
                                    <table id="myTable" class="table table-bordered" style="margin-bottom: 30px;">
                                        <thead style="background-color: #15a362; color: white;">
                                     
                                            <tr>
                                                <th>Tanggal dan Waktu</th>
                                                <th>Nama Alternatif</th>
                                                <th>Nilai SAW</th>
                                                <th>Status</th>
                                                
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            
                                        <?php if (empty($final_scores)): ?>
                                            <tr>
                                                <td colspan="5" style="text-align:center;">Tidak ada data</td>
                                            </tr>
                                        <?php else: ?>
                                            <?php foreach ($final_scores as $final_score): ?>
                                            <tr>
                                                <td><?= $final_score['tanggal_input'] ?></td>
                                                <td><?= $final_score['nama_alternatif'] ?></td>
                                                <td><?= round($final_score['score'], 4) ?></td>
                                                <?php
                                                    if ($final_score['score'] >= 0.8){
                                                    echo "<td><button type='button' class='btn btn-outline-success btn-sm' disabled>Layak</button</td>";
                                                    } else {
                                                        echo "<td><button type='button' class='btn btn-outline-danger btn-sm' disabled>Tidak Layak</button></td>";
                                                    }
                                                    
                                                    if ($_SESSION['akses'] === 'manajer') {
                                                        echo "<td><a type='button' class='btn btn-danger btn-sm' href='index.php?page=transaksi&hapus=" .  $final_score['id_alternatif']  . "' onclick='return confirmDelete()'>Hapus</a>";
                                                    } else {
                                                        echo "<td>";
                                                    }
                                                            
                                                        
                                                    $alternatif = $row['nama_alternatif'];
                                                    $sql1 = "SELECT * FROM data_alternatif WHERE nama_alternatif = '$alternatif'";

                                                    $result1 = $conn->query($sql1);
                                                            
                                                        if ($result1 && $result1->num_rows > 0) {
                                                            $c = [
                                                                'c1' => '-',  //Default nilai
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
                                                                    
                                                                $result2 = $conn->query($sql2);
                                                                    
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
                                                            
                                                                
                                                        
                                                        ?>
                                                    <button type="button" 
                                                    class='btn btn-success btn-sm'  
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#detailModal"
                                                    data-kode="<?php echo $final_score['nama_alternatif']; ?>"
                                                    data-c1="<?php echo $c['c1']; ?>"
                                                    data-c2="<?php echo $c['c2']; ?>"
                                                    data-c3="<?php echo $c['c3']; ?>"
                                                    data-c4="<?php echo $c['c4']; ?>"
                                                    data-nilaiuji="<?php echo number_format($final_score['score'], 4); ?>"
                                                    data-hasiluji="<?php echo $final_score['score'] >= 0.8 ? 'Layak' : 'Tidak Layak'; ?>">
                                                        Info lengkap
                                                    </button>
                                                    </td>
                                            </tr>
                                            <?php } endforeach; ?>
                                        <?php endif; ?>

                                        </tbody>
                                    </table>
                                    
                                </div><!--//table-responsive-->                          
                            
                       </div><!--//tab-pane-->

                    <div class="tab-pane fade" id="orders-paid" role="tabpanel" aria-labelledby="orders-paid-tab">
                                <h4 style="color: #15a362; margin-bottom: 15px; border-bottom: 3px solid #15a362; padding-bottom: 5px; font-weight: bold;">Tabel Data Normalisasi</h4>
							    <div class="table-responsive">
                                    <table id="myTable2" class="table table-bordered" style="margin-bottom: 30px;">
                                        <thead style="background-color: #15a362; color: white;">
                                             
                                            <tr>
                                                <th>Nama Alternatif</th>
                                                <th>C1 (<?= $criteria_types['c1'] ?>)</th>
                                                <th>C2 (<?= $criteria_types['c2'] ?>)</th>
                                                <th>C3 (<?= $criteria_types['c3'] ?>)</th>
                                                <th>C4 (<?= $criteria_types['c4'] ?>)</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            
                                            <?php if (empty($normalized_data)): ?>
                                                <tr>
                                                    <td colspan="5" style="text-align:center;">Tidak ada data</td>
                                                </tr>
                                            <?php else: ?>
                                                <?php foreach ($normalized_data as $row): ?>
                                                <tr>
                                                    <td><?= $row['nama_alternatif'] ?></td>
                                                    <td><?= round($row['c1'], 4) ?></td>
                                                    <td><?= round($row['c2'], 4) ?></td>
                                                    <td><?= round($row['c3'], 4) ?></td>
                                                    <td><?= round($row['c4'], 4) ?></td>
                                                </tr>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </tbody>
                                    </table>
                                </div>
                    </div>
                    

			        <div class="tab-pane fade" id="orders-pending" role="tabpanel" aria-labelledby="orders-pending-tab">
                        
                            <h4 style="color: #15a362; margin-bottom: 15px; border-bottom: 3px solid #15a362; padding-bottom: 5px; font-weight: bold;">Tabel Hitung Nilai</h4>
							    <div class="table-responsive">
                                    <table id="myTable3" class="table table-bordered" style="margin-bottom: 30px;">
                                        <thead style="background-color: #15a362; color: white;">
                                             
                                            <tr>
                                                <th>Alternatif</th>
                                                <th>V1 (C1 * <?= $weights['c1'] ?>)</th>
                                                <th>V2 (C2 * <?= $weights['c2'] ?>)</th>
                                                <th>V3 (C3 * <?= $weights['c3'] ?>)</th>
                                                <th>V4 (C4 * <?= $weights['c4'] ?>)</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            
                                            <?php if (empty($normalized_data)): ?>
                                                <tr>
                                                    <td colspan="5" style="text-align:center;">Tidak ada data</td>
                                                </tr>
                                            <?php else: ?>
                                                <?php foreach ($calculation_values as $row): ?>
                                                <tr>
                                                    <td><?= $row['nama_alternatif'] ?></td>
                                                    <td><?= round($row['c1'], 4) ?></td>
                                                    <td><?= round($row['c2'], 4) ?></td>
                                                    <td><?= round($row['c3'], 4) ?></td>
                                                    <td><?= round($row['c4'], 4) ?></td>
                                                </tr>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </tbody>
                                    </table>
                                </div>
                    </div>



                    
			        

			        <div class="tab-pane fade" id="orders-cancelled" role="tabpanel" aria-labelledby="orders-cancelled-tab">
                            <h4 style="color: #15a362; margin-bottom: 15px; border-bottom: 3px solid #15a362; padding-bottom: 5px; font-weight: bold;">Tabel Data Normalisasi</h4>
							    <div class="table-responsive">
                                    <table id="myTable4" class="table table-bordered" style="margin-bottom: 30px;">
                                        <thead style="background-color: #15a362; color: white;">
                                             
                                            <tr>
                                                <th>Nama Alternatif</th>
                                                <th>C1 (<?= $criteria_types['c1'] ?>)</th>
                                                <th>C2 (<?= $criteria_types['c2'] ?>)</th>
                                                <th>C3 (<?= $criteria_types['c3'] ?>)</th>
                                                <th>C4 (<?= $criteria_types['c4'] ?>)</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            
                                            <?php if (empty($normalized_data)): ?>
                                                <tr>
                                                    <td colspan="5" style="text-align:center;">Tidak ada data</td>
                                                </tr>
                                            <?php else: ?>
                                                <?php foreach ($normalized_data as $row): ?>
                                                <tr>
                                                    <td><?= $row['nama_alternatif'] ?></td>
                                                    <td><?= round($row['c1'], 4) ?></td>
                                                    <td><?= round($row['c2'], 4) ?></td>
                                                    <td><?= round($row['c3'], 4) ?></td>
                                                    <td><?= round($row['c4'], 4) ?></td>
                                                </tr>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </tbody>
                                    </table>
                                </div>
                                                   
                                    

                                <br>
                                <br>
                                <br>
                                <h4 style="color: #15a362; margin-bottom: 15px; border-bottom: 3px solid #15a362; padding-bottom: 5px; font-weight: bold;">Tabel Hitung Nilai</h4>
                                    
							    <div class="table-responsive">
                                    <table id="myTable5" class="table table-bordered" style="margin-bottom: 30px;">
                                        <thead style="background-color: #15a362; color: white;">
                                             
                                            <tr>
                                                <th>Alternatif</th>
                                                <th>V1 (C1 * <?= $weights['c1'] ?>)</th>
                                                <th>V2 (C2 * <?= $weights['c2'] ?>)</th>
                                                <th>V3 (C3 * <?= $weights['c3'] ?>)</th>
                                                <th>V4 (C4 * <?= $weights['c4'] ?>)</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            
                                            <?php if (empty($normalized_data)): ?>
                                                <tr>
                                                    <td colspan="5" style="text-align:center;">Tidak ada data</td>
                                                </tr>
                                            <?php else: ?>
                                                <?php foreach ($calculation_values as $row): ?>
                                                <tr>
                                                    <td><?= $row['nama_alternatif'] ?></td>
                                                    <td><?= round($row['c1'], 4) ?></td>
                                                    <td><?= round($row['c2'], 4) ?></td>
                                                    <td><?= round($row['c3'], 4) ?></td>
                                                    <td><?= round($row['c4'], 4) ?></td>
                                                </tr>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </tbody>
                                    </table>

                                </div>

                                <br>
                                <br>
                                <br>
                                <h4 style="color: #15a362; margin-bottom: 15px; border-bottom: 3px solid #15a362; padding-bottom: 5px; font-weight: bold;">Hasil Perhitungan Nilai SAW</h4>
                                    <table id="myTable6" class="table table-bordered" style="margin-bottom: 30px;">
                                        <thead style="background-color: #15a362; color: white;">
                                     
                                            <tr>
                                                <th>Tanggal dan Waktu</th>
                                                <th>Nama Alternatif</th>
                                                <th>Nilai SAW</th>
                                                <th>Status</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            
                                        <?php if (empty($final_scores)): ?>
                                            <tr>
                                                <td colspan="5" style="text-align:center;">Tidak ada data</td>
                                            </tr>
                                        <?php else: ?>
                                            <?php foreach ($final_scores as $final_score): ?>
                                            <tr>
                                                <td><?= $final_score['tanggal_input'] ?></td>
                                                <td><?= $final_score['nama_alternatif'] ?></td>
                                                <td><?= round($final_score['score'], 4) ?></td>
                                                <?php
                                                    if ($final_score['score'] >= 0.8){
                                                    echo "<td><button type='button' class='btn btn-outline-success btn-sm' disabled>Layak</button</td>";
                                                    } else {
                                                        echo "<td><button type='button' class='btn btn-outline-danger btn-sm' disabled>Tidak Layak</button></td>";
                                                    }
                                                    if ($_SESSION['akses']  === 'manajer') {
                                                    echo "<td><a type='button' class='btn btn-danger btn-sm' href='index.php?page=transaksi&hapus=" .  $final_score['id_alternatif']  . "' onclick='return confirmDelete()'>Hapus</a>";
                                               
                                                    } else {
                                                        echo "<td>";
                                                    }
                                                            
                                                        
                                                    $alternatif = $row['nama_alternatif'];
                                                    $sql1 = "SELECT * FROM data_alternatif WHERE nama_alternatif = '$alternatif'";

                                                    $result1 = $conn->query($sql1);
                                                            
                                                        if ($result1 && $result1->num_rows > 0) {
                                                            $c = [
                                                                'c1' => '-',  //Default nilai
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
                                                                    
                                                                $result2 = $conn->query($sql2);
                                                                    
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
                                                            
                                                                
                                                        
                                                        ?>
                                                    <button type="button" 
                                                    class='btn btn-success btn-sm'  
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#detailModal"
                                                    data-kode="<?php echo $final_score['nama_alternatif']; ?>"
                                                    data-c1="<?php echo $c['c1']; ?>"
                                                    data-c2="<?php echo $c['c2']; ?>"
                                                    data-c3="<?php echo $c['c3']; ?>"
                                                    data-c4="<?php echo $c['c4']; ?>"
                                                    data-nilaiuji="<?php echo number_format($final_score['score'], 4); ?>"
                                                    data-hasiluji="<?php echo $final_score['score'] >= 0.8 ? 'Layak' : 'Tidak Layak'; ?>">
                                                        Info lengkap
                                                    </button>
                                                    </td>
                                            </tr>
                                            <?php } endforeach; ?>
                                        <?php endif; ?>

                                        </tbody>
                                    </table>
                                
                                </div>
                    </div>
    </div>



    
                <!-- Modal Tambah Kriteria -->
                <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content" style="border-radius: 15px; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1); overflow: hidden; border: none;">
                            <div class="modal-header" style="background: linear-gradient(135deg, #15a362, #128751); color: white; border-bottom: none; padding: 1.5rem;">
                                <h5 class="modal-title" id="myModalLabel">Tambah Alternatif</h5>
                                
                            </div>
                            <div class="modal-body" style="background-color: #ffffff; padding: 30px;">
                                <form action="index.php?page=transaksi" method="POST">
                                    
                                <div class="form-group" style="margin-bottom: 20px;">
                                        <label for="alternatif" style="font-weight: bold; color: #15a362;">KODE TANAMAN</label>
                                        <input type="text" class="form-control" id="alternatif" name="alternatif" required>
                                    </div>
                                    <div class="form-group" style="margin-bottom: 20px;">
                                        <label for="c1" style="font-weight: bold; color: #15a362;">KESEHATAN TANAMAN</label>
                                        <select class="form-control" id="c1" name="c1" required>
                                            <option value="">--PILIH KESEHATAN TANAMAN--</option>
                                            <?php
                                            $sqlc1 = "SELECT 
                                                        db.nama AS nama_bobot,
                                                        dk.nama AS nama_kriteria,
                                                        dk.nilai
                                                    FROM 
                                                        data_bobot db
                                                    JOIN 
                                                        data_kriteria dk ON db.id_bobot = dk.id_bobot WHERE db.nama = '(C1) KESEHATAN TANAMAN';";
                                            $resultc1 = $conn->query($sqlc1);
                                            if ($resultc1->num_rows > 0) {
                                                while ($rowc1 = $resultc1->fetch_assoc()) {
                                                    echo '<option value="' . $rowc1["nilai"] . '">' . $rowc1["nama_kriteria"] . '</option>';
                                                }
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="form-group" style="margin-bottom: 20px;">
                                        <label for="c2" style="font-weight: bold; color: #15a362;">PERTUMBUHAN TANAMAN</label>
                                        <select class="form-control" id="c2" name="c2" required>
                                            <option value="">--PILIH PERTUMBUHAN TANAMAN--</option>
                                            <?php
                                            $sqlc2 = "SELECT 
                                                        db.nama AS nama_bobot,
                                                        dk.nama AS nama_kriteria,
                                                        dk.nilai
                                                    FROM 
                                                        data_bobot db
                                                    JOIN 
                                                        data_kriteria dk ON db.id_bobot = dk.id_bobot WHERE db.nama = '(C2) PERTUMBUHAN TANAMAN';";
                                            $resultc2 = $conn->query($sqlc2);
                                            if ($resultc2->num_rows > 0) {
                                                while ($rowc2 = $resultc2->fetch_assoc()) {
                                                    echo '<option value="' . $rowc2["nilai"] . '">' . $rowc2["nama_kriteria"] . '</option>';
                                                }
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="form-group" style="margin-bottom: 20px;">
                                        <label for="c3" style="font-weight: bold; color: #15a362;">WARNA DAUN</label>
                                        <select class="form-control" id="c3" name="c3" required>
                                            <option value="">--PILIH WARNA DAUN--</option>
                                            <?php
                                            $sqlc3 = "SELECT 
                                                        db.nama AS nama_bobot,
                                                        dk.nama AS nama_kriteria,
                                                        dk.nilai
                                                    FROM 
                                                        data_bobot db
                                                    JOIN 
                                                        data_kriteria dk ON db.id_bobot = dk.id_bobot WHERE db.nama = '(C3) WARNA DAUN';";
                                            $resultc3 = $conn->query($sqlc3);
                                            if ($resultc3->num_rows > 0) {
                                                while ($rowc3 = $resultc3->fetch_assoc()) {
                                                    echo '<option value="' . $rowc3["nilai"] . '">' . $rowc3["nama_kriteria"] . '</option>';
                                                }
                                            }
                                            ?>
                                        </select>
                                    </div>              
                                    <div class="form-group" style="margin-bottom: 20px;">
                                        <label for="c4" style="font-weight: bold; color: #15a362;">KUALITAS AKAR</label>
                                        <select class="form-control" id="c4" name="c4" required>
                                            <option value="">--PILIH KUALITAS AKAR--</option>
                                            <?php
                                            $sqlc4 = "SELECT 
                                                        db.nama AS nama_bobot,
                                                        dk.nama AS nama_kriteria,
                                                        dk.nilai
                                                    FROM 
                                                        data_bobot db
                                                    JOIN 
                                                        data_kriteria dk ON db.id_bobot = dk.id_bobot WHERE db.nama = '(C4) KUALITAS AKAR';";
                                            $resultc4 = $conn->query($sqlc4);
                                            if ($resultc4->num_rows > 0) {
                                                while ($rowc4 = $resultc4->fetch_assoc()) {
                                                    echo '<option value="' . $rowc4["nilai"] . '">' . $rowc4["nama_kriteria"] . '</option>';
                                                }
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="modal-footer" style="border-top: none; background-color: #f8f9fa; padding: 15px; display: flex; justify-content: center;">
                        
                                        <button type="button" class="btn btn-danger" data-dismiss="modal">Batal</button>
                                        <button type="submit" name="tambah" class="btn btn-success">Simpan</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                
                <div class="modal fade" id="detailModal" tabindex="-1" aria-labelledby="detailModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content" style="border-radius: 15px; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1); overflow: hidden; border: none;">
                            <div class="modal-header" style="background: linear-gradient(135deg, #15a362, #128751); color: white; border-bottom: none; padding: 1.5rem; position: relative;">
                                <h5 class="modal-title" id="detailModalLabel">Detail Data</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body" style="background-color: #ffffff; padding: 30px;">
                                <div class="detail-item" style="padding: 15px 0; border-bottom: 1px solid #eaeaea; display: flex; align-items: center;">
                                    <i class="detail-icon bi bi-code" style="font-size: 24px; color: #15a362; margin-right: 15px;"></i>
                                    <div>
                                        <div class="detail-title" style="font-weight: bold; color: #15a362; margin-bottom: 5px; font-size: 18px;">Kode</div>
                                        <div class="detail-content detail-content-kode" style="color: #555; font-size: 16px;"></div>
                                    </div>
                                </div>
                                <div class="detail-item" style="padding: 15px 0; border-bottom: 1px solid #eaeaea; display: flex; align-items: center;">
                                    <i class="detail-icon bi bi-heart-pulse" style="font-size: 24px; color: #15a362; margin-right: 15px;"></i>
                                    <div>
                                        <div class="detail-title" style="font-weight: bold; color: #15a362; margin-bottom: 5px; font-size: 18px;">Kesehatan Tanaman</div>
                                        <div class="detail-content detail-content-c1" style="color: #555; font-size: 16px;"></div>
                                    </div>
                                </div>
                                <div class="detail-item" style="padding: 15px 0; border-bottom: 1px solid #eaeaea; display: flex; align-items: center;">
                                    <i class="detail-icon bi bi-bar-chart" style="font-size: 24px; color: #15a362; margin-right: 15px;"></i>
                                    <div>
                                        <div class="detail-title" style="font-weight: bold; color: #15a362; margin-bottom: 5px; font-size: 18px;">Pertumbuhan Tanaman</div>
                                        <div class="detail-content detail-content-c2" style="color: #555; font-size: 16px;"></div>
                                    </div>
                                </div>
                                <div class="detail-item" style="padding: 15px 0; border-bottom: 1px solid #eaeaea; display: flex; align-items: center;">
                                    <i class="detail-icon bi bi-palette" style="font-size: 24px; color: #15a362; margin-right: 15px;"></i>
                                    <div>
                                        <div class="detail-title" style="font-weight: bold; color: #15a362; margin-bottom: 5px; font-size: 18px;">Warna Daun</div>
                                        <div class="detail-content detail-content-c3" style="color: #555; font-size: 16px;"></div>
                                    </div>
                                </div>
                                <div class="detail-item" style="padding: 15px 0; border-bottom: 1px solid #eaeaea; display: flex; align-items: center;">
                                    <i class="detail-icon bi bi-tree" style="font-size: 24px; color: #15a362; margin-right: 15px;"></i>
                                    <div>
                                        <div class="detail-title" style="font-weight: bold; color: #15a362; margin-bottom: 5px; font-size: 18px;">Kualitas Akar</div>
                                        <div class="detail-content detail-content-c4" style="color: #555; font-size: 16px;"></div>
                                    </div>
                                </div>
                                <div class="detail-item" style="padding: 15px 0; border-bottom: 1px solid #eaeaea; display: flex; align-items: center;">
                                    <i class="detail-icon bi bi-graph-up-arrow" style="font-size: 24px; color: #15a362; margin-right: 15px;"></i>
                                    <div>
                                        <div class="detail-title" style="font-weight: bold; color: #15a362; margin-bottom: 5px; font-size: 18px;">Nilai Uji</div>
                                        <div class="detail-content detail-content-nilaiuji" style="color: #555; font-size: 16px;"></div>
                                    </div>
                                </div>
                                <div class="detail-item" style="padding: 15px 0; display: flex; align-items: center;">
                                    <i class="detail-icon bi bi-check-circle" style="font-size: 24px; color: #15a362; margin-right: 15px;"></i>
                                    <div>
                                        <div class="detail-title" style="font-weight: bold; color: #15a362; margin-bottom: 5px; font-size: 18px;">Hasil Uji</div>
                                        <div class="detail-content detail-content-hasiluji" style="color: #555; font-size: 16px;"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer" style="border-top: none; background-color: #f8f9fa; padding: 15px; display: flex; justify-content: center;">
                                <button type="button" class="btn btn-custom" style="background-color: #15a362; color: white; border-radius: 50px; padding: 10px 30px;" data-bs-dismiss="modal">Tutup</button>
                            </div>
                        </div>
                    </div>
                </div>

                <script>
                document.addEventListener('DOMContentLoaded', function() {
                    var detailModal = document.getElementById('detailModal');
                    detailModal.addEventListener('show.bs.modal', function (event) {
                        var button = event.relatedTarget; // Button that triggered the modal
                        var kode = button.getAttribute('data-kode');
                        var c1 = button.getAttribute('data-c1');
                        var c2 = button.getAttribute('data-c2');
                        var c3 = button.getAttribute('data-c3');
                        var c4 = button.getAttribute('data-c4');
                        var nilaiUji = button.getAttribute('data-nilaiuji');
                        var hasilUji = button.getAttribute('data-hasiluji');

                        var modalTitle = detailModal.querySelector('.modal-title');
                        var kodeElement = detailModal.querySelector('.detail-content-kode');
                        var c1Element = detailModal.querySelector('.detail-content-c1');
                        var c2Element = detailModal.querySelector('.detail-content-c2');
                        var c3Element = detailModal.querySelector('.detail-content-c3');
                        var c4Element = detailModal.querySelector('.detail-content-c4');
                        var nilaiUjiElement = detailModal.querySelector('.detail-content-nilaiuji');
                        var hasilUjiElement = detailModal.querySelector('.detail-content-hasiluji');

                        modalTitle.textContent = 'Detail Data ' + kode;
                        kodeElement.textContent = kode;
                        c1Element.textContent = c1;
                        c2Element.textContent = c2;
                        c3Element.textContent = c3;
                        c4Element.textContent = c4;
                        nilaiUjiElement.textContent = nilaiUji;
                        hasilUjiElement.textContent = hasilUji;
                    });
                });
                </script>
                
                <script>
                    function confirmDeleteAll() {
                        return confirm("Apakah Anda yakin ingin menghapus semua alternatif ini?");
                    }
                    function confirmDelete() {
                        return confirm("Apakah Anda yakin ingin menghapus data ini?");
                    }
                </script>

                                
                <!-- jQuery -->
                <script src="assets/plugins/datatables/jquery-3.6.0.min.js"></script>

                <!-- Bootstrap JS -->
                <script src="assets/plugins/datatables/bootstrap.bundle.min.js"></script>

                <!-- DataTables JS -->
                <script src="assets/plugins/datatables/jquery.dataTables.min.js"></script>
                <script src="assets/plugins/datatables/dataTables.bootstrap5.min.js"></script>

                <!-- Inisialisasi DataTables -->
                <script>
                    $(document).ready(function() {
                        $('#myTable').DataTable({
                            "paging": true,
                            "searching": true,
                            "ordering": true,
                            "info": true,
                            "pageLength": 5,
                            "lengthChange": false,
                            "order": [[0, 'desc']], 
                            "language": {
                                "search": "Cari:",
                                "paginate": {
                                    "next": "Berikutnya",
                                    "previous": "Sebelumnya"
                                },
                                "info": "Menampilkan _START_ sampai _END_ dari _TOTAL_ data"
                            }
                        });



                        $('#myTable2').DataTable({
                            "paging": true,
                            "searching": true,
                            "ordering": true,
                            "info": true,
                            "pageLength": 5,
                            "lengthChange": false,
                            "language": {
                                "search": "Cari:",
                                "paginate": {
                                    "next": "Berikutnya",
                                    "previous": "Sebelumnya"
                                },
                                "info": "Menampilkan _START_ sampai _END_ dari _TOTAL_ data"
                            }
                        });



                        $('#myTable3').DataTable({
                            "paging": true,
                            "searching": true,
                            "ordering": true,
                            "info": true,
                            "pageLength": 5,
                            "lengthChange": false,
                            "language": {
                                "search": "Cari:",
                                "paginate": {
                                    "next": "Berikutnya",
                                    "previous": "Sebelumnya"
                                },
                                "info": "Menampilkan _START_ sampai _END_ dari _TOTAL_ data"
                            }
                        });

                        



                        $('#myTable4').DataTable({
                            "paging": true,
                            "searching": true,
                            "ordering": true,
                            "info": true,
                            "pageLength": 5,
                            "lengthChange": false,
                            "language": {
                                "search": "Cari:",
                                "paginate": {
                                    "next": "Berikutnya",
                                    "previous": "Sebelumnya"
                                },
                                "info": "Menampilkan _START_ sampai _END_ dari _TOTAL_ data"
                            }
                        });

                        



                        $('#myTable5').DataTable({
                            "paging": true,
                            "searching": true,
                            "ordering": true,
                            "info": true,
                            "pageLength": 5,
                            "lengthChange": false,
                            "language": {
                                "search": "Cari:",
                                "paginate": {
                                    "next": "Berikutnya",
                                    "previous": "Sebelumnya"
                                },
                                "info": "Menampilkan _START_ sampai _END_ dari _TOTAL_ data"
                            }
                        });


                        



                        $('#myTable6').DataTable({
                            "paging": true,
                            "searching": true,
                            "ordering": true,
                            "info": true,
                            "pageLength": 5,
                            "lengthChange": false,
                            "language": {
                                "search": "Cari:",
                                "paginate": {
                                    "next": "Berikutnya",
                                    "previous": "Sebelumnya"
                                },
                                "info": "Menampilkan _START_ sampai _END_ dari _TOTAL_ data"
                            }
                        });
                    });
                    
                    
                    // Menonaktifkan semua alert error di DataTables
                    $.fn.dataTable.ext.errMode = 'none';


                </script>

                <script>
                    function confirmDelete() {
                        return confirm('Apakah Anda yakin ingin menghapus data ini?');
                    }
                </script>

<?php
$conn->close();
?>
