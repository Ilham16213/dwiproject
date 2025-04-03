<?php
    require_once 'models/user_model.php';

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $controller = new User_Model($conn);

    if (isset($_POST['tambah'])) {
        $pengguna = $_POST['pengguna'];
        $jabatan = $_POST['jabatan'];
        $status = $_POST['status'];
        $katasandi = md5('12345');
        $profil = "default.jpg";

        // Query untuk memeriksa apakah pengguna sudah ada
        $query = "SELECT * FROM data_pengguna WHERE pengguna = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $pengguna);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($jabatan === 'manajer'){
            // Validasi: Jika data sudah ada
            if ($result->num_rows > 0) {
                // Tindakan jika data sudah ada
                header("Location: index.php?page=user&double");
                exit();
            }
            
            if ($controller->tambahUser($pengguna, $jabatan, $status, $katasandi, $profil)) {
                header("Location: index.php?page=user&insert");
                exit();
            } else {
                echo "<script>alert('Gagal memasukkan data');</script>";
            }
        }        

        if ($jabatan === 'staff'){
            // Validasi: Jika data sudah ada
            if ($result->num_rows > 0) {
                // Tindakan jika data sudah ada
                header("Location: index.php?page=pengguna&double");
                exit();
            }
            
            if ($controller->tambahUser($pengguna, $jabatan, $status, $katasandi, $profil)) {
                header("Location: index.php?page=pengguna&insert");
                exit();
            } else {
                echo "<script>alert('Gagal memasukkan data');</script>";
            }
        }        
    }
    
    }

    if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['hapus'])) {
        
        $controller = new User_Model($conn);

        // Mendapatkan id_kelas yang akan dihapus
        $id_user = $_GET['hapus'];
        $jabatan = $_GET['status'];

        // Memanggil fungsi hapusKelas
        if ($jabatan === 'staff'){
            if ($controller->hapusUser($id_user)) {
                header("Location: index.php?page=pengguna&delete");
                exit();
            } else {
                echo "<script>alert('Gagal menghapus data');</script>";
            }
        }
        
        if ($jabatan === 'manajer'){
            if ($controller->hapusUser($id_user)) {
                header("Location: index.php?page=user&delete");
                exit();
            } else {
                echo "<script>alert('Gagal menghapus data');</script>";
            }
        }
    }


?>
