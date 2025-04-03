<?php
// Query untuk memeriksa data
$sql = "SELECT COUNT(*) as count FROM data_bobot";
$result = $conn->query($sql);

// Ambil hasil query
$dataExists = 'false';
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    if ($row['count'] > 0) {
        $dataExists = 'true';
    }
}




require_once 'controller/bobot_controller.php';
?>

<h1 class="app-page-title">Bobot</h1>

<?php if ($_SESSION['akses']  === 'manajer') { ?>	
<div class="row g-3 mb-4 align-items-center justify-content-between">
    <div class="col-auto">
        <div class="page-utilities">
            <div class="row g-2 justify-content-start justify-content-md-end align-items-center">
                <div class="col-auto">
                    
                <?php if ($dataExists == 'false') { ?>
                    <a href="index.php?page=inputbobot">
                        <button type="submit" name="tambah" class="btn-sm app-btn-secondary form-control-sm">Tambah Bobot</button>
                    </a>
                    <?php } else {
                    ?>
                    
                        <!-- <form method="POST" action="index.php?page=bobot">
                            <button type="submit" class="btn-sm app-btn-secondary form-control-sm" name="delete_all" onclick='return confirmDelete()'>Hapus Bobot</button>
                        </form> -->
                       
                        <a href="index.php?page=inputbobot">
                            <button type="submit" name="tambah" class="btn-sm app-btn-secondary form-control-sm">Update Bobot</button>
                        </a>
                    <?php
                    }?>
				</div>
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
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        Data Berhasil Diupdate Ke Database.
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div> 
<?php
} 
?>


                <div class="table-responsive">
                    <table class="table table-bordered">
					<?php
						$sql = "SELECT * FROM data_bobot";
						$result = $conn->query($sql);

						if ($result->num_rows > 0) {
							echo "<thead><tr>";
								echo "<th>No</th>";
								echo "<th>Nama</th>";
								echo "<th>Bobot</th>";
                                
                                if ($_SESSION['akses']  === 'manajer') { 	
								echo "<th>Status</th>";
                                }
							echo "</tr><thead><tbody>";
							// Output bobot sebagai td
							$result->data_seek(0); // Reset pointer ke awal hasil query
                            $no=1;
							while($row = $result->fetch_assoc()) {
								echo "<tr>";
								echo "<td>" . $no. "</td>";
								echo "<td>" . $row["nama"]. "</td>";
								echo "<td>" . $row["bobot"]. "</td>";
                                
                                if ($_SESSION['akses']  === 'manajer') { 	
								    echo "<td>";
                                    $keterangan = $row["status"];
                                    echo '<div class="btn-group" role="group" aria-label="Basic mixed styles example">';
                                    if ($keterangan == "cost") {
                                        echo '<button type="button" class="btn btn-success btn-sm">Cost</button>';
                                        echo '<a href="index.php?page=bobot&update='. $row["id_bobot"] .'&keterangan=benefit" type="button" class="btn btn-outline-success btn-sm">Benefit</a>';
                                    } else if ($keterangan == "benefit") {
                                        echo '<a href="index.php?page=bobot&update='. $row["id_bobot"] .'&keterangan=cost" type="button" class="btn btn-outline-success btn-sm">Cost</a>';
                                        echo '<button type="button" class="btn btn-success btn-sm">Benefit</button>';
                                    }

                                    echo "</td>";
                                }
                                echo '</div>';

                                
							    echo "</tr>";
                                $no++;
							}
							echo "</tbody>";
						} else {
							echo "<tr><td colspan='2'>Tidak ada data</td></tr>";
						}
						?>
                    </table>
                </div>

	<script>
		function confirmDelete() {
			return confirm("Apakah Anda yakin ingin menghapus data ini?");
		}
	</script>

	<script>
        function validateForm() {
            const inputs = document.querySelectorAll('input[name="weights[]"]');
            let total = 0;
            inputs.forEach(input => {
                total += parseFloat(input.value) || 0;
            });
            if (total !== 1) {
                alert('Total bobot harus sama dengan 1.');
                return false;
            }
            return true;
        }
        
        function addInput() {
            const container = document.getElementById('input-container');
            const input = document.createElement('input');
            input.type = 'number';
            input.name = 'weights[]';
            input.step = '0.01';
            input.min = '0';
            input.className = 'form-control my-2';
            input.placeholder = 'Masukkan bobot';
            container.appendChild(input);
        }

        function resetForm() {
            const container = document.getElementById('input-container');
            container.innerHTML = '';
            addInput();
        }
    </script>

<script>
  function confirmDelete() {
      return confirm("Jika anda menghapus bobot, maka semua data pada program akan dihapus, disarankan melakukan backup rekap data terlebih dahulu");
  }
  </script>
