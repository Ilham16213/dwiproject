<?php
require_once 'controller/user_controller.php';
?>
<h1 class="app-page-title">Pengguna</h1>
	
    <div class="row g-3 mb-4 align-items-center justify-content-between">
        <div class="col-auto">
            <div class="page-utilities">
                <div class="row g-2 justify-content-start justify-content-md-end align-items-center">
                    <div class="col-auto">
                                    <button type="submit" class="btn-sm app-btn-secondary form-control-sm"  data-toggle="modal" data-target="#myModal">Tambah Pengguna</button>
                                
                    </div>
                </div>
            </div>
        </div>
    </div>

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
} else if(isset($_GET['double'])) {
    ?>
        <div class="alert alert-primary alert-dismissible fade show" role="alert">
            Data Pengguna Sudah ada pada Database.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div> 
    <?php
    } 
?>
           
                                <h4 style="color: #15a362; margin-bottom: 15px; border-bottom: 3px solid #15a362; padding-bottom: 5px; font-weight: bold;"></h4>
                                <div class="table-responsive">
                                    <table id="myTable" class="table table-bordered" style="margin-bottom: 30px;">
                                        <thead style="background-color: #15a362; color: white;">
                                     
                                            <tr>
                                                <th>No</th>
                                                <th>Pengguna</th>
                                                <th>Jabatan</th>
                                                <th>Status</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                            // Proses perubahan status
                                            if (isset($_POST['id_pengguna'])) {
                                                $id_pengguna = $_POST['id_pengguna'];
                                                $status_baru = $_POST['status'];
                                                
                                                $sql = "UPDATE data_pengguna SET status = '$status_baru' WHERE id_pengguna = $id_pengguna";
                                                
                                                if ($conn->query($sql) === TRUE) {
                                                    echo "Status berhasil diubah.";
                                                } else {
                                                    echo "Error: " . $conn->error;
                                                }
                                                exit;
                                            }

                                            // Ambil data pengguna dari database
                                            $sql = "SELECT * 
                                            FROM data_pengguna 
                                            WHERE akses NOT IN ('staff', 'owner');";
                                            $result = $conn->query($sql);
                                        ?>

                                        <?php 
                                        
                                        $no = 1;
                                        while($row = $result->fetch_assoc()) { ?>
                                            <tr>
                                                <td><?php echo $no; $no++;?></td>
                                                <td><?php echo $row['pengguna']; ?></td>
                                                <td><?php echo $row['akses']; ?></td>
                                                <td>
                                                    <div style="position: relative; display: inline-block; width: 40px; height: 20px;">
                                                        <input 
                                                            type="checkbox" 
                                                            <?php echo $row['status'] == 'aktif' ? 'checked' : ''; ?>
                                                            style="
                                                                width: 40px; 
                                                                height: 20px; 
                                                                background-color: <?php echo $row['status'] == 'aktif' ? '#15a362' : '#f00'; ?>; 
                                                                border-radius: 20px; 
                                                                border: none; 
                                                                appearance: none; 
                                                                cursor: pointer; 
                                                                position: relative; 
                                                                transition: background-color 0.3s ease-in-out;
                                                                outline: none;"
                                                            onclick="toggleStatus(<?php echo $row['id_pengguna']; ?>, this)">
                                                        <span 
                                                            style="
                                                                position: absolute; 
                                                                top: 2px; 
                                                                left: 2px; 
                                                                width: 16px; 
                                                                height: 16px; 
                                                                background-color: white; 
                                                                border-radius: 50%; 
                                                                transition: transform 0.3s ease-in-out;
                                                                transform: <?php echo $row['status'] == 'aktif' ? 'translateX(20px)' : 'translateX(0)'; ?>;
                                                                pointer-events: none;">
                                                        </span>
                                                    </div>
                                                </td>
                                                
                                                <td>
                                                    <a type='button' class='btn btn-danger btn-sm' href='index.php?page=pengguna&status=manajer&hapus=<?php echo $row['id_pengguna']; ?>' onclick='return confirmDelete()'>Hapus</a>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                            
                                        </tbody>
                                    </table>
                                    
                                </div><!--//table-responsive-->                          
                            
								

				
                <!-- Modal Tambah User -->
                <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">             
                        <div class="modal-content" style="border-radius: 15px; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1); overflow: hidden; border: none;">
                            <div class="modal-header" style="background: linear-gradient(135deg, #15a362, #128751); color: white; border-bottom: none; padding: 1.5rem;">
                                <h5 class="modal-title" id="myModalLabel">Tambah Pengguna</h5>
                                
                            </div>
                            <div class="modal-body" style="background-color: #ffffff; padding: 30px;">
                                <form action="index.php?page=pengguna" method="POST">
                                                    
                                    <div class="form-group" style="margin-bottom: 20px;">
                                        <label for="pengguna" style="font-weight: bold; color: #15a362;">PENGGUNA</label>
                                        <input type="text" class="form-control" id="pengguna" name="pengguna" required>
                                    </div>
                                    <div class="form-group" style="margin-bottom: 20px;">
                                        <label for="jabatan" style="font-weight: bold; color: #15a362;">JABATAN</label>
                                        <select class="form-control" id="jabatan" name="jabatan" required readonly>
                                            <option value="manajer" selected>MANAJER</option>
                                        </select>

									</div>
                                    <div class="form-group" style="margin-bottom: 20px;">
                                        <label for="status" style="font-weight: bold; color: #15a362;">STATUS AKUN</label>
										<select class="form-control" id="status" name="status" required>
											<option value="">--PILIH STATUS AKUN--</option>
											<option value="aktif">AKTIF</option>
											<option value="nonaktif">TIDAK AKTIF</option>
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

				
				
                <script>
                    $(document).ready(function() {
                        $('#myTable').DataTable({
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
                    document.getElementById('flexSwitchCheckDefault').addEventListener('change', function() {
                    if (this.checked) {
                        this.nextElementSibling.textContent = 'ON';
                    } else {
                        this.nextElementSibling.textContent = 'OFF';
                    }
                    });
                </script>


    <script>
        function toggleStatus(id_pengguna, checkbox) {
            const newStatus = checkbox.checked ? 'aktif' : 'nonaktif';
            const xhr = new XMLHttpRequest();
            xhr.open("POST", "", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.onreadystatechange = function() {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    checkbox.style.backgroundColor = newStatus === 'aktif' ? '#15a362' : '#f00';
                    checkbox.nextElementSibling.style.transform = newStatus === 'aktif' ? 'translateX(20px)' : 'translateX(0)';
                }
            };
            xhr.send("id_pengguna=" + id_pengguna + "&status=" + newStatus);
        }
    </script>
<?php
$conn->close();
?>
