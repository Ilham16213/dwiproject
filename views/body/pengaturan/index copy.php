<div class="app-content pt-3 p-md-3 p-lg-4">
		    <div class="container-xl">			    
			    <h1 class="app-page-title">Pengaturan Akun</h1>
			    <hr class="mb-4">
                <div class="row g-4 settings-section">

                    <div class="col-12 col-md-4 text-center">
                        <!-- Foto Profil -->
                        <img src="assets/images/user-f.png" alt="Foto Profile" class="img-fluid rounded-circle shadow-lg mt-2" id="profilePicture" style="max-width: 200px; border: 3px solid #15a362;">
                        
                        <!-- Form untuk mengubah profil -->
                        <form action="upload_profile.php" method="POST" enctype="multipart/form-data" class="row mt-4 align-items-center justify-content-center">
                            <div class="col-12 mb-2">
                                <label for="profileImage" class="form-label" style="font-weight: bold; color: #15a362;">Upload Foto Baru</label>
                                <input type="file" name="profileImage" id="profileImage" class="form-control form-control-sm" style="border-color: #15a362; padding: 10px;">
                            </div>
                            <div class="col-12">
                                <button type="submit" class="btn btn-sm btn-success w-100" style="background-color: #15a362; border-color: #15a362;">Ubah Profil</button>
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
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="myModalLabel">Ubah Kata Sandi</h5>
                                
                            </div>
                            <div class="modal-body">
                                <form action="index.php?page=pengguna" method="POST">
                                    
                                    <div class="form-group">
                                        <label for="pengguna">Kata Sandi Lama</label>
                                        <input type="text" class="form-control" id="pengguna" name="pengguna" required>
                                    </div>
                                    <div class="form-group">
										<label for="jabatan">Kata Sandi Baru</label>
                                        <select class="form-control" id="jabatan" name="jabatan" required readonly>
                                            <option value="staff" selected>STAFF</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
										<label for="jabatan">Konfirmasi Sandi Baru</label>
                                        <select class="form-control" id="jabatan" name="jabatan" required readonly>
                                            <option value="staff" selected>STAFF</option>
                                        </select>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-danger" data-dismiss="modal">Batal</button>
                                        <button type="submit" name="tambah" class="btn btn-success">Simpan</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
