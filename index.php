<?php

    require('config/database.php');
    require('views/main/header.php');


    if(isset($_GET['page'])){


    switch($_GET['page']){
        case 'home': include 'views/body/dashboard.php'; break;
        
        case 'pengguna': include 'views/body/user/staff.php'; break;
        
        case 'user': include 'views/body/user/manager.php'; break;

        case 'bobot': include 'views/body/bobot/index.php'; break;
        case 'inputbobot': include 'views/body/bobot/tambah.php'; break;

        case 'kriteria': include 'views/body/kriteria/index.php'; break;

        case 'transaksi': include 'views/body/kelola/index.php'; break;

        case 'rekap': include 'views/body/pdf/index.php'; break;

        case 'setting': include 'views/body/pengaturan/index.php'; break;
    }

    }else{
        echo '<meta http-equiv="refresh" content="0;url=index.php?page=home" />';
    }


    require('views/main/footer.php');
?>