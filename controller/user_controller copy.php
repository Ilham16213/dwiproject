<?php
    require_once 'models/user_model.php';

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $controller = new User_Model($conn);

    if (isset($_POST['tambah'])) {
        $pengguna = $_POST['pengguna'];
        $jabatan = $_POST['jabatan'];
        $status = $_POST['status'];
        $katasandi = md5('12345');

        
        if ($controller->tambahUser($pengguna, $jabatan, $status, $katasandi)) {
            header("Location: index.php?page=pengguna&insert");
            exit();
        } else {
            echo "<script>alert('Gagal memasukkan data');</script>";
        }
    }
    
    }

    if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['hapus'])) {
        
        $controller = new User_Model($conn);

        // Mendapatkan id_kelas yang akan dihapus
        $id_user = $_GET['hapus'];

        // Memanggil fungsi hapusKelas
        if ($controller->hapusUser($id_user)) {
            header("Location: index.php?page=pengguna&delete");
            exit();
        } else {
            echo "<script>alert('Gagal menghapus data');</script>";
        }
    }


?>
