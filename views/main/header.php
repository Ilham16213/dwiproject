<?php
ob_start();
session_start();
// Periksa apakah pengguna belum login
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
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
	<link href="assets/plugins/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
    
    <!-- App CSS -->  
    <link id="theme-style" rel="stylesheet" href="assets/css/portal.css">

    <link href="assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet">
	<script src="assets/plugins/bootstrap/js/jquery.min.js"></script>
	<script src="assets/plugins/bootstrap/js/popper.min.js"></script>
	<script src="assets/js/bootstrap.min.js"></script>
	

    <!-- DataTables CSS -->
    <link href="assets/plugins/datatables/dataTables.bootstrap5.min.css" rel="stylesheet">

	<style>
		/* Ubah warna latar belakang dan teks tombol pagination saat aktif */
		.dataTables_paginate .pagination .page-item.active .page-link {
			background-color: #15a362; /* Warna latar belakang hijau */
			color: white !important;   /* Warna teks putih */
			border-color: #15a362;     /* Warna border hijau */
		}

		/* Ubah warna latar belakang dan teks tombol pagination lainnya */
		.dataTables_paginate .pagination .page-item .page-link {
			background-color: white;   /* Warna latar belakang putih */
			color: #15a362 !important; /* Warna teks hijau */
			border-color: #15a362;     /* Warna border hijau */
		}

		/* Tambahkan efek hover pada tombol pagination */
		.dataTables_paginate .pagination .page-item .page-link:hover {
			background-color: #e6f4ed; /* Warna latar belakang hijau muda saat hover */
			color: #15a362 !important; /* Warna teks hijau */
			border-color: #15a362;     /* Warna border hijau */
		}

		/* Ubah warna tombol 'Sebelumnya' dan 'Berikutnya' ketika disabled */
		.dataTables_paginate .pagination .page-item.disabled .page-link {
			background-color: #f8f9fa; /* Warna latar belakang abu-abu muda */
			color: #6c757d !important; /* Warna teks abu-abu */
			border-color: #dee2e6;     /* Warna border abu-abu */
		}

	</style>


</head> 

<body class="app">   	
    <header class="app-header fixed-top">	   	            
        <div class="app-header-inner">  
	        <div class="container-fluid py-2">
		        <div class="app-header-content"> 
		            <div class="row justify-content-between align-items-center">
			        
				    <div class="col-auto">
					    <a id="sidepanel-toggler" class="sidepanel-toggler d-inline-block d-xl-none" href="#">
						    <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 30 30" role="img"><title>Menu</title><path stroke="currentColor" stroke-linecap="round" stroke-miterlimit="10" stroke-width="2" d="M4 7h22M4 15h22M4 23h22"></path></svg>
					    </a>
				    </div><!--//col-->

					<div class="app-utilities col-auto">
						<div class="app-utility-item app-user-dropdown dropdown">
							<a class="nav-link nav-profile d-flex align-items-center pe-0" href="#" data-bs-toggle="dropdown">
								
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
								<img src="<?php echo $profile_image; ?>" alt="user profile" class="rounded-circle shadow" style="width: 35px; height: 35px; border: 2px solid #15a362;">
								<span class="d-none d-md-block dropdown-toggle ps-2 fw-bold" style="color: #15A362; font-size: 14px;"><?php echo strtoupper($_SESSION['username']); ?></span>
							</a><!-- End Profile Image Icon -->

							<ul class="dropdown-menu dropdown-menu-end shadow-sm" aria-labelledby="user-dropdown-toggle" style="min-width: 180px; border-radius: 10px;">
								<li><a class="dropdown-item d-flex align-items-center" href="index.php?page=setting">
									<i class="bi bi-gear-fill me-2" style="color: #15a362; font-size: 16px;"></i> Pengaturan Akun</a></li>
								<li><hr class="dropdown-divider"></li>
								<li><a class="dropdown-item d-flex align-items-center" href="logout.php" onclick="return confirmkeluar()">
									<i class="bi bi-box-arrow-right me-2" style="color: #15a362; font-size: 16px;"></i> Keluar</a></li>
							</ul>
						</div><!--//app-user-dropdown--> 
					</div><!--//app-utilities-->
		        </div><!--//row-->
	            </div><!--//app-header-content-->
	        </div><!--//container-fluid-->
        </div><!--//app-header-inner-->
        <div id="app-sidepanel" class="app-sidepanel"> 
	        <div id="sidepanel-drop" class="sidepanel-drop"></div>
	        <div class="sidepanel-inner d-flex flex-column">
		        <a href="#" id="sidepanel-close" class="sidepanel-close d-xl-none">&times;</a>
		        <div class="app-branding">
		        <img class="logo-icon me-2" src="assets/images/DNA-Logo.png" alt="logo" style="width: 190px; height: 60px;">
		        </div><!--//app-branding-->  
				<?php
					$activePage = $_GET['page'];
				?>
			    <nav id="app-nav-main" class="app-nav app-nav-main flex-grow-1">
				    <ul class="app-menu list-unstyled accordion" id="menu-accordion">
					    <li class="nav-item">
					        <!--//Bootstrap Icons: https://icons.getbootstrap.com/ -->
					        <a class="nav-link <?php echo ($activePage == 'home') ? 'active' : ''; ?>" href="index.php?page=home">
						        <span class="nav-icon">
								<svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" fill="currentColor" class="bi bi-house" viewBox="0 0 16 16">
									<path fill-rule="evenodd" d="M8 3.293l6 6V13.5a.5.5 0 0 1-.5.5H10a.5.5 0 0 1-.5-.5V10a.5.5 0 0 0-.5-.5h-2a.5.5 0 0 0-.5.5v3.5a.5.5 0 0 1-.5.5H2.5a.5.5 0 0 1-.5-.5V9.293l6-6zM7.293 1.5a1 1 0 0 1 1.414 0l7 7a1 1 0 0 1-1.414 1.414L8 3.707 1.707 9.914A1 1 0 0 1 .293 8.5l7-7z"/>
								</svg>

						         </span>
		                         <span class="nav-link-text">Beranda</span>
					        </a><!--//nav-link-->
					    </li><!--//nav-item-->
						<?php
							if ($_SESSION['akses'] !== 'staff') {
						?>	
					    <li class="nav-item">
					        <!--//Bootstrap Icons: https://icons.getbootstrap.com/ -->
					        <a class="nav-link <?php echo ($activePage == 'bobot') ? 'active' : ''; ?>" href="index.php?page=bobot">
						        <span class="nav-icon">
								<svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" fill="currentColor" class="bi bi-sliders" viewBox="0 0 16 16">
  <path fill-rule="evenodd" d="M6.5 0a.5.5 0 0 1 .5.5v10.982a1.5 1.5 0 0 1 0 2.036V15.5a.5.5 0 0 1-1 0v-1.982a1.5 1.5 0 0 1 0-2.036V.5a.5.5 0 0 1 .5-.5zM10.5 0a.5.5 0 0 1 .5.5v4.982a1.5 1.5 0 0 1 0 2.036V15.5a.5.5 0 0 1-1 0V7.518a1.5 1.5 0 0 1 0-2.036V.5a.5.5 0 0 1 .5-.5zM4.5 3a.5.5 0 0 1 .5.5v7.982a1.5 1.5 0 0 1 0 2.036V15.5a.5.5 0 0 1-1 0v-2.982a1.5 1.5 0 0 1 0-2.036V3.5a.5.5 0 0 1 .5-.5z"/>
</svg>

						         </span>
		                         <span class="nav-link-text">Bobot</span>
					        </a><!--//nav-link-->
					    </li><!--//nav-item-->
					    <li class="nav-item">
					        <!--//Bootstrap Icons: https://icons.getbootstrap.com/ -->
					        <a class="nav-link <?php echo ($activePage == 'kriteria') ? 'active' : ''; ?>" href="index.php?page=kriteria">
						        <span class="nav-icon">
								<svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" fill="currentColor" class="bi bi-check2-circle" viewBox="0 0 16 16">
  <path fill-rule="evenodd" d="M2.5 8a5.5 5.5 0 1 1 11 0 5.5 5.5 0 0 1-11 0zm12.5 0a7 7 0 1 0-14 0 7 7 0 0 0 14 0z"/>
  <path fill-rule="evenodd" d="M10.854 6.354a.5.5 0 0 0-.708-.708L7.5 8.293 6.354 7.146a.5.5 0 1 0-.708.708l1.5 1.5a.5.5 0 0 0 .708 0l3-3z"/>
</svg>

						         </span>
		                         <span class="nav-link-text">Kriteria</span>
					        </a><!--//nav-link-->
					    </li><!--//nav-item-->
						
						<?php
							}
						?>
					    <li class="nav-item">
					        <!--//Bootstrap Icons: https://icons.getbootstrap.com/ -->
					        <a class="nav-link <?php echo ($activePage == 'transaksi') ? 'active' : ''; ?>" href="index.php?page=transaksi">
						        <span class="nav-icon">
								<svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" fill="currentColor" class="bi bi-journal-bookmark" viewBox="0 0 16 16">
  <path fill-rule="evenodd" d="M6 8V1h1v7l.803-.401 3.597-1.8V1h1v7l.97-.485a.5.5 0 0 1 .857.314v7.382a.5.5 0 0 1-.757.429L8.5 13.5l-4.97 2.486a.5.5 0 0 1-.757-.43V8.829a.5.5 0 0 1 .857-.314l.97.485z"/>
  <path fill-rule="evenodd" d="M4.5 0a.5.5 0 0 1 .5.5v14a.5.5 0 0 1-1 0v-14a.5.5 0 0 1 .5-.5zM10.5 0a.5.5 0 0 1 .5.5v14a.5.5 0 0 1-1 0v-14a.5.5 0 0 1 .5-.5z"/>
</svg>

						         </span>
		                         <span class="nav-link-text">Transaksi</span>
					        </a><!--//nav-link-->
					    </li><!--//nav-item-->	    
					    <li class="nav-item">
					        <!--//Bootstrap Icons: https://icons.getbootstrap.com/ -->
					        <a class="nav-link <?php echo ($activePage == 'rekap') ? 'active' : ''; ?>" href="index.php?page=rekap">
						        <span class="nav-icon">
								<svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" fill="currentColor" class="bi bi-clipboard-data" viewBox="0 0 16 16">
  <path fill-rule="evenodd" d="M6.5 0a1.5 1.5 0 0 1 1.4.916h1.2A1.5 1.5 0 0 1 10.5 0h1A1.5 1.5 0 0 1 13 1.5v1h-.5a.5.5 0 0 0 0 1H13v11a1.5 1.5 0 0 1-1.5 1.5h-7A1.5 1.5 0 0 1 3 14.5V3h.5a.5.5 0 0 0 0-1H3v-1A1.5 1.5 0 0 1 4.5 0h1zm2 2V1a.5.5 0 0 0-.5-.5h-1A.5.5 0 0 0 6.5 1v1h2zm-5 4a.5.5 0 0 1 .5-.5h1A.5.5 0 0 1 5.5 6v5a.5.5 0 0 1-1 0V6zm3 2a.5.5 0 0 1 .5-.5h1A.5.5 0 0 1 9.5 8v5a.5.5 0 0 1-1 0V8zm3-2a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v5a.5.5 0 0 1-1 0V6z"/>
</svg>

						         </span>
		                         <span class="nav-link-text">Rekap</span>
					        </a><!--//nav-link-->
					    </li><!--//nav-item-->		
				    </ul><!--//app-menu-->
			    </nav><!--//app-nav-->
			    <div class="app-sidepanel-footer">
				    <nav class="app-nav app-nav-footer">
					    <ul class="app-menu footer-menu list-unstyled">
							
						<?php
							if ($_SESSION['akses'] === 'manajer') {
						?>
						    <li class="nav-item">
						        <!--//Bootstrap Icons: https://icons.getbootstrap.com/ -->
						        <a class="nav-link <?php echo ($activePage == 'pengguna') ? 'active' : ''; ?>" href="index.php?page=pengguna">
							        <span class="nav-icon">
									<svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" fill="currentColor" class="bi bi-person" viewBox="0 0 16 16">
  <path d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6z"/>
  <path fill-rule="evenodd" d="M8 9a5 5 0 0 0-5 5v.5a.5.5 0 0 0 .5.5h9a.5.5 0 0 0 .5-.5V14a5 5 0 0 0-5-5z"/>
</svg>

									</span>
									<span class="nav-link-text">Pengguna</span>
						        </a><!--//nav-link-->
						    </li><!--//nav-item-->
							
						<?php
							}
						?>

						
<?php
							if ($_SESSION['akses'] === 'owner') {
						?>
						    <li class="nav-item">
						        <!--//Bootstrap Icons: https://icons.getbootstrap.com/ -->
						        <a class="nav-link <?php echo ($activePage == 'user') ? 'active' : ''; ?>" href="index.php?page=user">
							        <span class="nav-icon">
									<svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" fill="currentColor" class="bi bi-person" viewBox="0 0 16 16">
  <path d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6z"/>
  <path fill-rule="evenodd" d="M8 9a5 5 0 0 0-5 5v.5a.5.5 0 0 0 .5.5h9a.5.5 0 0 0 .5-.5V14a5 5 0 0 0-5-5z"/>
</svg>

									</span>
									<span class="nav-link-text">Pengguna</span>
						        </a><!--//nav-link-->
						    </li><!--//nav-item-->
							
						<?php
							}
						?>
						    <li class="nav-item">
						        <!--//Bootstrap Icons: https://icons.getbootstrap.com/ -->
						        <a class="nav-link" href="logout.php" onclick='return confirmkeluar()'>
							        <span class="nav-icon">
									<svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" fill="currentColor" class="bi bi-box-arrow-right" viewBox="0 0 16 16">
  <path fill-rule="evenodd" d="M8.5 2a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 0 1H9v11h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1-.5-.5v-12zM6.354 11.854a.5.5 0 0 0 .708-.708L5.707 10H14.5a.5.5 0 0 0 0-1H5.707l1.355-1.146a.5.5 0 1 0-.708-.708l-2 2a.5.5 0 0 0 0 .708l2 2z"/>
</svg>


							        </span>
			                        <span class="nav-link-text">Keluar</span>
						        </a><!--//nav-link-->
						    </li><!--//nav-item-->
					    </ul><!--//footer-menu-->
				    </nav>
			    </div><!--//app-sidepanel-footer-->
		       
	        </div><!--//sidepanel-inner-->
	    </div><!--//app-sidepanel-->
    </header><!--//app-header-->
    
    <div class="app-wrapper">
	    
	    <div class="app-content pt-3 p-md-3 p-lg-4">
		    <div class="container-xl">
			    