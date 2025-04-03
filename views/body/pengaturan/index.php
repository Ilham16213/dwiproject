<?php
    require_once 'controller/pengguna_controller.php';
?>
    
<div class="app-content pt-3 p-md-3 p-lg-4">
		    <div class="container-xl">			    
			    <h1 class="app-page-title">Pengaturan Akun</h1>
			    <hr class="mb-4">
                                    
                    <?php
                    if(isset($_GET['erroroldpass'])) {
                    ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            Kata sandi lama yang anda masukan salah.
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div> 
                    <?php
                    } else if(isset($_GET['errorconfirm'])) {
                    ?>
                        <div class="alert alert-primary alert-dismissible fade show" role="alert">
                            kata sandi baru dan konfirmasi tidak cocok.
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div> 
                    <?php
                    } else if(isset($_GET['update'])) {
                    ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            kata sandi berhasil diubah.
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div> 
                    <?php
                    } 
                    ?>
                <div class="row g-4 settings-section">

                    <div class="col-12 col-md-4 text-center">
                        <?php
                            $id = $_SESSION['id']; // ID pengguna dari session

                            // Query untuk mendapatkan data profil
                            $sql = "SELECT * FROM data_pengguna WHERE id_pengguna = $id";

                            // Jalankan query
                            $result = $conn->query($sql);

                            // Periksa apakah ada data yang ditemukan
                            if ($result->num_rows > 0) {
                                // Ambil data pengguna
                                $row = $result->fetch_assoc();
                                $namaprofil = $row['profil'];
                            }
                            // Path gambar profile
                            $profile_image = "assets/images/profiles/" . $namaprofil;

                            // Jika file gambar tidak ada, gunakan gambar alternatif
                            if (!file_exists($profile_image)) {
                                $profile_image = "assets/images/profiles/default.jpg"; // Gambar alternatif (default)
                            }
                        ?>
  
                        <!-- Foto Profil -->
                        <img src="<?php echo $profile_image; ?>" alt="Foto Profile" class="img-fluid rounded-circle shadow-lg mt-2" id="profilePicture" style="width: 195px; height: 195px; object-fit: cover; border: 3px solid #15a362;">
  
                        
                        <!-- Form untuk mengubah profil -->
                        <form action="" method="post" enctype="multipart/form-data" class="row mt-4 align-items-center justify-content-center">
                            <div class="col-12 mb-2">
                                <label for="profileImage" class="form-label" style="font-weight: bold; color: #15a362;">Upload Foto Baru</label>
                                <input type="file" name="gambar" id="gambar" accept="image/*" class="form-control form-control-sm" style="border-color: #15a362; padding: 10px;" required>
                            </div>
                            <div class="col-12">
                                <input type="submit" name="upload" class="btn btn-sm btn-success w-100" style="background-color: #15a362; border-color: #15a362;" value="Ubah Profil">
                            </div>
                        </form>
                    </div>



                                    
                    <div class="col-12 col-md-8">
                        <div class="app-card app-card-settings shadow-sm p-4" style="background-color: white; border: 1px solid #15a362;">
                            <div class="app-card-body">
                                <form class="settings-form">
                                    <div class="mb-3">
                                        <label for="setting-input-1" class="form-label" style="color: #15a362;">Nama Pengguna</label>
                                        <input type="text" class="form-control" id="setting-input-1" value="<?php echo $_SESSION['username']?>" readonly="" style="border-color: #15a362;">
                                    </div>
                                    <div class="mb-3">
                                        <label for="setting-input-2" class="form-label" style="color: #15a362;">Jabatan</label>
                                        <input type="text" class="form-control" id="setting-input-2" value="<?php echo $_SESSION['akses']?>" readonly="" style="border-color: #15a362;">
                                    </div>
                                    <div class="mb-3">
                                        <label for="setting-input-3" class="form-label" style="color: #15a362;">Status Akun</label>
                                        <input type="email" class="form-control" id="setting-input-3" value="<?php echo $_SESSION['status']?>" readonly="" style="border-color: #15a362;">
                                    </div>
                                    <a type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModal" style="background-color: #15a362; border-color: #15a362;">Ubah Kata Sandi</a>
                                </form>
                            </div><!--//app-card-body-->
                        </div><!--//app-card-->
                    </div>
                </div><!--//row-->
                
                <!--//row-->
                
                <!--//row-->
                
                <!--//row-->
			    
		    </div><!--//container-fluid-->
	    </div>


                <!-- Modal Ubah sandi -->
                <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content" style="border-radius: 15px; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1); overflow: hidden; border: none;">
                            <div class="modal-header" style="background: linear-gradient(135deg, #15a362, #128751); color: white; border-bottom: none; padding: 1.5rem;">
                                <h5 class="modal-title" id="myModalLabel">Ubah Kata Sandi</h5>
                                
                            </div>
                            <div class="modal-body" style="background-color: #ffffff; padding: 30px;">
                                <form action="index.php?page=setting" method="POST">
                                    
                                    <div class="form-group" style="margin-bottom: 20px;">
                                        <label for="passlama" style="font-weight: bold; color: #15a362;">KATA SANDI LAMA</label>    
                                        <input type="password" class="form-control" id="passlama" name="passlama" required>
                                    </div>
                                    <div class="form-group" style="margin-bottom: 20px;">
                                        <label for="passbaru" style="font-weight: bold; color: #15a362;">KATA SANDI BARU</label>    
                                        <input type="password" class="form-control" id="passbaru" name="passbaru" required>
                                    </div>
                                    <div class="form-group" style="margin-bottom: 20px;">
                                        <label for="passkonfirmasi" style="font-weight: bold; color: #15a362;">KONFIRMASI KATA SANDI</label>
                                        <input type="password" class="form-control" id="passkonfirmasi" name="passkonfirmasi" required>
                                    </div>
                                    <div class="modal-footer" style="border-top: none; background-color: #f8f9fa; padding: 15px; display: flex; justify-content: center;">
                                        <button type="button" class="btn btn-danger" data-dismiss="modal">Batal</button>
                                        <button type="submit" name="update" class="btn btn-success">Simpan</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
