<?php
    require_once 'models/transaksi_model.php';

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $controller = new Transaksi_Model($conn);

    // Memanggil fungsi tambahKelas jika tombol "Tambah Kelas" ditekan
    if (isset($_POST['tambah'])) {
        $alternatif = $_POST['alternatif'];
        $c1 = $_POST['c1'];
        $c2 = $_POST['c2'];
        $c3 = $_POST['c3'];
        $c4 = $_POST['c4'];

        $query = "SELECT * FROM data_alternatif WHERE nama_alternatif = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $alternatif);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            echo "<script>
                    alert('Kode alternatif sudah ada, silakan gunakan kode yang berbeda.');
                    window.location.href = 'index.php?page=transaksi';
                  </script>";
            exit(); // Tambahkan ini untuk memastikan proses berhenti
        }
        
    

        $datetime = new DateTime('now', new DateTimeZone('Asia/Jakarta'));
        $tanggal_input = $datetime->format('Y-m-d H:i:s'); // Format: 2024-08-12 14:23:00



        if ($controller->tambahTransaksi($alternatif, $c1, $c2, $c3, $c4, $tanggal_input)) {
            header("Location: index.php?page=transaksi&insert");
            exit();
        } else {
            echo "<script>alert('Gagal memasukkan data');</script>";
        }
    }
    
    if (isset($_POST['update'])) {
        $id_kelas = $_POST['id_kelas'];
        $nama_kelas = $_POST['nama_kelas'];
        $wali_kelas = $_POST['wali_kelas'];
        
        if ($controller->updateKelas($id_kelas, $nama_kelas, $wali_kelas)) {
            header("Location: index.php?page=datakelas&update");
            exit();
        } else {
            echo "<script>alert('Gagal mengupdate data');</script>";
        }
    }
    }

    if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['hapus'])) {
        
        $controller = new Transaksi_Model($conn);

        // Mendapatkan id_kelas yang akan dihapus
        $id_alternatif = $_GET['hapus'];

        // Memanggil fungsi hapusKelas
        if ($controller->hapusTransaksi($id_alternatif)) {
            header("Location: index.php?page=transaksi&delete");
            exit();
        } else {
            echo "<script>alert('Gagal menghapus data');</script>";
        }
    }


    
    if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['hapusall'])) {
        
        $controller = new Transaksi_Model($conn);
    
        // Mendapatkan id_kelas yang akan dihapus
        $id_alternatif = $_GET['hapusall'];
    
        // Memanggil fungsi hapusKelas
        if ($controller->hapussemuaTransaksi($id_alternatif)) {
            header("Location: index.php?page=transaksi&delete");
            exit();
        } else {
            echo "<script>alert('Gagal menghapus data');</script>";
        }
    }
?>
