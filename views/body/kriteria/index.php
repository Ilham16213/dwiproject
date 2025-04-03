<?php
require_once 'controller/kriteria_controller.php';
?>

<h1 class="app-page-title">Kriteria</h1>

<?php if ($_SESSION['akses']  === 'manajer') { 	?>
<div class="row g-3 mb-4 align-items-center justify-content-between">
    <div class="col-auto">
        <div class="page-utilities">
            <div class="row g-2 justify-content-start justify-content-md-end align-items-center">
                <div class="col-auto">
								
                                <button type="submit" class="btn-sm app-btn-secondary form-control-sm"  data-toggle="modal" data-target="#myModal">Tambah Kriteria</button>
                        
                            
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
    <div class="alert alert-primary alert-dismissible fade show" role="alert">
        Data Berhasil Diupdate Ke Database.
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div> 
<?php
} 
?>


        <!-- Tabel Kesehatan Tanaman -->
        <div class="mb-4">
            <h4 style="color: #15a362; margin-bottom: 15px; border-bottom: 3px solid #15a362; padding-bottom: 5px; font-weight: bold;">C1. Kesehatan Tanaman</h4>
            <table class="table table-bordered" style="margin-bottom: 30px;">
                <thead style="background-color: #15a362; color: white;">
                    <tr>
                        <th>Sub Kriteria</th>
                        <th class="text-center">Nilai Kriteria</th>
                        
                        <?php if ($_SESSION['akses']  === 'manajer') { 	?>
                            <th>Aksi</th>
                        <?php } ?>
                    </tr>
                </thead>
                <tbody>
                        <?php
                            $sql = "SELECT data_kriteria.id_kriteria AS id,data_bobot.nama AS nama_bobot, data_kriteria.nama AS nama_kriteria, data_kriteria.nilai FROM data_kriteria JOIN data_bobot ON data_kriteria.id_bobot = data_bobot.id_bobot WHERE data_bobot.nama = '(C1) KESEHATAN TANAMAN'";
                            $result = $conn->query($sql);

                            if ($result->num_rows > 0) {
                                while($row = $result->fetch_assoc()) {
                                    echo "<tr>";
                                    echo "<td>" . $row["nama_kriteria"] . "</td>";
                                    echo "<td class='text-center'>" . $row["nilai"] . "</td>";
                                    
                                    if ($_SESSION['akses']  === 'manajer') { 
                                    echo "<td>";
                                    echo "<a type='button' class='btn btn-danger btn-sm' href='index.php?page=kriteria&hapus=" . $row["id"] . "' onclick='return confirmDelete()'>Hapus</a>";
                                    echo "</td>";
                                    }
                                    echo "</tr>";
                                }
                            } else {
                                echo "<tr><td colspan='5' class='text-center'>Tidak ada data</td></tr>";
                            }
                        ?>
                </tbody>
            </table>
        </div>

        <!-- Tabel Pertumbuhan Tanaman -->
        <div class="mb-4">
            <h4 style="color: #15a362; margin-bottom: 15px; border-bottom: 3px solid #15a362; padding-bottom: 5px; font-weight: bold;">C2. Pertumbuhan Tanaman</h4>
            <table class="table table-bordered" style="margin-bottom: 30px;">
                <thead style="background-color: #15a362; color: white;">
                    <tr>
                        <th>Sub Kriteria</th>
                        <th class="text-center">Nilai Kriteria</th>
                        
                        <?php if ($_SESSION['akses']  === 'manajer') { 	?>
                            <th>Aksi</th>
                        <?php } ?>
                    </tr>
                </thead>
                <tbody>
                        <?php
                            $sql = "SELECT data_kriteria.id_kriteria AS id,data_bobot.nama AS nama_bobot, data_kriteria.nama AS nama_kriteria, data_kriteria.nilai FROM data_kriteria JOIN data_bobot ON data_kriteria.id_bobot = data_bobot.id_bobot WHERE data_bobot.nama = '(C2) PERTUMBUHAN TANAMAN'";
                            $result = $conn->query($sql);

                            if ($result->num_rows > 0) {
                                while($row = $result->fetch_assoc()) {
                                    echo "<tr>";
                                    echo "<td>" . $row["nama_kriteria"] . "</td>";
                                    echo "<td class='text-center'>" . $row["nilai"] . "</td>";
                                    if ($_SESSION['akses']  === 'manajer') {
                                    echo "<td>";
                                    echo "<a type='button' class='btn btn-danger btn-sm' href='index.php?page=kriteria&hapus=" . $row["id"] . "' onclick='return confirmDelete()'>Hapus</a>";
                                    echo "</td>";
                                    }
                                    echo "</tr>";
                                }
                            } else {
                                echo "<tr><td colspan='5' class='text-center'>Tidak ada data</td></tr>";
                            }
                        ?>
                </tbody>
            </table>
        </div>

        <!-- Tabel Warna Daun -->
        <div class="mb-4">
            <h4 style="color: #15a362; margin-bottom: 15px; border-bottom: 3px solid #15a362; padding-bottom: 5px; font-weight: bold;">C3. Warna Daun</h4>
            <table class="table table-bordered" style="margin-bottom: 30px;">
                <thead style="background-color: #15a362; color: white;">
                    <tr>
                        <th>Sub Kriteria</th>
                        <th class="text-center">Nilai Kriteria</th>
                        
                        <?php if ($_SESSION['akses']  === 'manajer') { 	?>
                            <th>Aksi</th>
                        <?php } ?>
                    </tr>
                </thead>
                <tbody>
                        <?php
                            $sql = "SELECT data_kriteria.id_kriteria AS id,data_bobot.nama AS nama_bobot, data_kriteria.nama AS nama_kriteria, data_kriteria.nilai FROM data_kriteria JOIN data_bobot ON data_kriteria.id_bobot = data_bobot.id_bobot WHERE data_bobot.nama = '(C3) WARNA DAUN'";
                            $result = $conn->query($sql);

                            if ($result->num_rows > 0) {
                                while($row = $result->fetch_assoc()) {
                                    echo "<tr>";
                                    echo "<td>" . $row["nama_kriteria"] . "</td>";
                                    echo "<td class='text-center'>" . $row["nilai"] . "</td>";
                                    if ($_SESSION['akses']  === 'manajer') {
                                    echo "<td>";
                                    echo "<a type='button' class='btn btn-danger btn-sm' href='index.php?page=kriteria&hapus=" . $row["id"] . "' onclick='return confirmDelete()'>Hapus</a>";
                                    echo "</td>";
                                    }
                                    echo "</tr>";
                                }
                            } else {
                                echo "<tr><td colspan='5' class='text-center'>Tidak ada data</td></tr>";
                            }
                        ?>
                </tbody>
            </table>
        </div>

        <!-- Tabel Kualitas Akar -->
        <div class="mb-4">
            <h4 style="color: #15a362; margin-bottom: 15px; border-bottom: 3px solid #15a362; padding-bottom: 5px; font-weight: bold;">C4. Kualitas Akar</h4>
            <table class="table table-bordered" style="margin-bottom: 30px;">
                <thead style="background-color: #15a362; color: white;">
                    <tr>
                        <th>Sub Kriteria</th>
                        <th class="text-center">Nilai Kriteria</th>
                        
                        <?php if ($_SESSION['akses']  === 'manajer') { 	?>
                            <th>Aksi</th>
                        <?php } ?>
                    </tr>
                </thead>
                <tbody>
                        <?php
                            $sql = "SELECT data_kriteria.id_kriteria AS id,data_bobot.nama AS nama_bobot, data_kriteria.nama AS nama_kriteria, data_kriteria.nilai FROM data_kriteria JOIN data_bobot ON data_kriteria.id_bobot = data_bobot.id_bobot WHERE data_bobot.nama = '(C4) KUALITAS AKAR'";
                            $result = $conn->query($sql);

                            if ($result->num_rows > 0) {
                                while($row = $result->fetch_assoc()) {
                                    echo "<tr>";
                                    echo "<td>" . $row["nama_kriteria"] . "</td>";
                                    echo "<td class='text-center'>" . $row["nilai"] . "</td>";
                                    if ($_SESSION['akses']  === 'manajer') {
                                    echo "<td>";
                                    echo "<a type='button' class='btn btn-danger btn-sm' href='index.php?page=kriteria&hapus=" . $row["id"] . "' onclick='return confirmDelete()'>Hapus</a>";
                                    echo "</td>";
                                    }
                                    echo "</tr>";
                                }
                            } else {
                                echo "<tr><td colspan='5' class='text-center'>Tidak ada data</td></tr>";
                            }
                        ?>
                </tbody>
            </table>
        </div>


                <!-- Modal Tambah Kriteria -->
                <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">         
                        <div class="modal-content" style="border-radius: 15px; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1); overflow: hidden; border: none;">
                            <div class="modal-header" style="background: linear-gradient(135deg, #15a362, #128751); color: white; border-bottom: none; padding: 1.5rem;">
                                <h5 class="modal-title" id="myModalLabel">Tambah Kriteria</h5>
                                
                            </div>
                            <div class="modal-body" style="background-color: #ffffff; padding: 30px;">
                                <form action="index.php?page=kriteria" method="POST">
                                    <div class="form-group" style="margin-bottom: 20px;">
                                        <label for="id_bobot" style="font-weight: bold; color: #15a362;">KRITERIA</label>
                                        <select class="form-control" id="id_bobot" name="id_bobot" required>
                                            <option value="">--PILIH KRITERIA--</option>
                                            <?php
                                            $sqlbobot = "SELECT * FROM data_bobot";
                                            $resultbobot = $conn->query($sqlbobot);
                                            if ($resultbobot->num_rows > 0) {
                                                while ($rowbobot = $resultbobot->fetch_assoc()) {
                                                    echo '<option value="' . $rowbobot["id_bobot"] . '">' . $rowbobot["nama"] . '</option>';
                                                }
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="form-group" style="margin-bottom: 20px;">
                                        <label for="nama_kriteria" style="font-weight: bold; color: #15a362;">NAMA KRITERIA</label>
                                        <input type="text" class="form-control" id="nama_kriteria" name="nama_kriteria" required>
                                    </div>
                                    <div class="form-group" style="margin-bottom: 20px;">
                                        <label for="nilai" style="font-weight: bold; color: #15a362;">NILAI</label>
                                        <input type="number" class="form-control" id="nilai" name="nilai" required>
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
<script>
function confirmDelete() {
    return confirm("Apakah Anda yakin ingin menghapus data ini?");
}

$(document).ready(function() {
    $('a[data-target="#myModalubah"]').click(function(e) {
        e.preventDefault();
        var id = $(this).attr('href').split('=')[2];
        
        $.ajax({
            url: 'fetch_kriteria.php',
            type: 'GET',
            data: { id_kriteria: id },
            dataType: 'json',
            success: function(response) {
                $('#id_kriteria').val(response.id_kriteria);
                $('#id_bobot_ubah').val(response.id_bobot);
                $('#nama_kriteria_ubah').val(response.nama);
                $('#nilai_ubah').val(response.nilai);
                $('#myModalubah').modal('show');
            }
        });
    });
});
</script>

