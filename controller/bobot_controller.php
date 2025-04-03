<?php
    require_once($_SERVER['DOCUMENT_ROOT'] . '/DWIPROJECT/config/database.php');
    require_once($_SERVER['DOCUMENT_ROOT'] . '/DWIPROJECT/models/bobot_model.php');


    
    if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['update'])) {
        $controller = new Bobot_Model($conn);
        
        $id_bobot = $_GET['update'];
        $keterangan = $_GET['keterangan'];
    
        
        // Eksekusi query
        if ($controller->updateBobot($id_bobot, $keterangan)) {
            // Jika berhasil, arahkan kembali ke halaman coba.php
            header("Location: index.php?page=bobot&status");
            exit(); // Pastikan kode setelah ini tidak dijalankan
        } else {
            echo "<script>alert('Gagal update data');</script>";
        }
    
    
    
    }
        
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['bobot1']) ) {
        $controller = new Bobot_Model($conn);

        $bobot1 = $_POST['bobot1'];
        $bobot2 = $_POST['bobot2'];
        $bobot3 = $_POST['bobot3'];
        $bobot4 = $_POST['bobot4'];
            
            if ($controller->tambahBobot($bobot1, $bobot2, $bobot3, $bobot4)) {
                header("Location: index.php?page=bobot&insert");
                exit();
            }
        }

        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_all'])) {
            $sql = "DELETE FROM data_bobot";
            if ($conn->query($sql) === TRUE) {
                header("Location: index.php?page=bobot&delete");
                exit();
            }
        }
    ?>