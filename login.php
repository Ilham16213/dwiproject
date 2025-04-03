<?php
session_start();
require_once 'config/database.php';

// Periksa apakah pengguna sudah login
if (isset($_SESSION['username'])) {
    header("Location: index.php"); // Alihkan ke halaman dashboard jika sudah login
    exit();
}

// Periksa apakah formulir login disubmit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = md5($_POST['password']);

    // Periksa apakah username dan password valid (Anda harus memvalidasi ini melalui database)
    $query = "SELECT * FROM data_pengguna WHERE pengguna = '$username' AND katasandi = '$password'";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
		$row = $result->fetch_assoc();
		if ($username == $row['pengguna'] && $password == $row['katasandi']) {
			if ($row['status'] == 'nonaktif'){
				header("Location: login.php?error=nonaktif");
				exit();
			} else {
				$_SESSION['username'] = $username;
				$_SESSION['akses'] = $row['akses'];
				$_SESSION['status'] = $row['status'];
				$_SESSION['id'] = $row['id_pengguna'];
				$_SESSION['profil'] = $row['profil'];
				header("Location: index.php?page=home");
				exit();
			}
		}
	} else {
		header("Location: login.php?error=true");
	}
}
?>


<!DOCTYPE html>
<html lang="en"> 
<head>
    <title>DNA FAVORIT</title>
    
    <!-- Meta -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <meta name="description" content="Portal - Bootstrap 5 Admin Dashboard Template For Developers">
    <meta name="author" content="Xiaoying Riley at 3rd Wave Media">   
    <link rel="shortcut icon" href="assets/images/DNA-Logo-D.png"> 
    
    
    <!-- FontAwesome JS-->
    <script defer src="assets/plugins/fontawesome/js/all.min.js"></script>
    
    <!-- App CSS -->  
    <link id="theme-style" rel="stylesheet" href="assets/css/portal.css">

</head> 

<body class="app app-login p-0">    	
    <div class="row g-0 app-auth-wrapper">
	    <div class="col-12 col-md-7 col-lg-6 auth-main-col text-center p-5">
		    <div class="d-flex flex-column align-content-end">
			    <div class="app-auth-body mx-auto">	
					<div class="app-auth-branding mb-4">
						<a class="app-logo" href="index.html">
							<img class="logo-icon me-2" src="assets/images/DNA-Logo.png" alt="logo" style="width: 250px; height: 110px;">
						</a>
					</div>
					<br>
                    <?php
                        if (isset($_GET['error']) && $_GET['error'] === 'true') {
                          ?><div class="alert alert-danger alert-dismissible fade show" role="alert">
                            Login Gagal Akun tidak terdaftar
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                          </div>
                          <?php
                        } else if (isset($_GET['error']) && $_GET['error'] === 'nonaktif') {
							?><div class="alert alert-primary alert-dismissible fade show" role="alert">
							  Login Gagal Akun tidak aktif
							  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
							</div>
							<?php
						  }  else {
                    ?>
                    <p class="text-center small">Silakan login ke akun anda</p>
                    <?php } ?>
			        <div class="auth-form-container text-start">
						<form class="auth-form login-form" method="POST" >         
							<div class="username mb-3">
								<label class="sr-only" for="username">Nama Pengguna</label>
								<input id="username" name="username" type="text" class="form-control signin-email" placeholder="Nama Pengguna" required="required">
							</div><!--//form-group-->
							<div class="password mb-3">
								<label class="sr-only" for="password">Kata Sandi</label>
								<input id="password" name="password" type="password" class="form-control signin-password" placeholder="Kata Sandi" required="required">
							</div><!--//form-group-->
							<div class="text-center">
								<button type="submit" class="btn app-btn-primary w-100 theme-btn mx-auto">Masuk</button>
							</div>
						</form>
						
					
					</div><!--//auth-form-container-->	

			    </div><!--//auth-body-->
		    
			    <footer class="app-auth-footer">
				    <div class="container text-center py-3">
				         <!--/* This template is free as long as you keep the footer attribution link. If you'd like to use the template without the attribution link, you can buy the commercial license via our website: themes.3rdwavemedia.com Thank you for your support. :) */-->
           				 <small class="copyright">&copy;2024 by <a class="app-link" href="https://google.com/search?q=Universitas+Pamulang" target="_blank">Universitas Pamulang</a> X DNA Favorit</small>
				    </div>
			    </footer><!--//app-auth-footer-->	
		    </div><!--//flex-column-->   
	    </div><!--//auth-main-col-->
	    <div class="col-12 col-md-5 col-lg-6 h-100 auth-background-col">
		    <div class="auth-background-holder">
		    </div>
		    <div class="auth-background-mask"></div>
		    <div class="auth-background-overlay p-3 p-lg-5">
			    <div class="d-flex flex-column align-content-end h-100">
				    <div class="h-100"></div>
				    <div class="overlay-content p-3 p-lg-4 rounded">
					    <h5 class="mb-3 overlay-title">DNA FAVORIT</h5>
					    <div>Web ini digunakan untuk menjadi pendukung dalam menunjang keputusan untuk memilih tanaman yang layak atau tidak layak ekspor.</div>
				    </div>
				</div>
		    </div><!--//auth-background-overlay-->
	    </div><!--//auth-background-col-->
    
    </div><!--//row-->

	<script src="assets/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>

</body>
</html> 

