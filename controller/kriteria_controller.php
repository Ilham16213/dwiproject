<?php
    require_once 'models/kriteria_model.php';

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $controller = new Kriteria_Model($conn);

    // Memanggil fungsi tambahKelas jika tombol "Tambah Kelas" ditekan
    if (isset($_POST['tambah'])) {
        $id_bobot = $_POST['id_bobot'];
        $nama_kriteria = $_POST['nama_kriteria'];
        $nilai = $_POST['nilai'];
        
        if ($controller->tambahKriteria($id_bobot, $nama_kriteria, $nilai)) {
            header("Location: index.php?page=kriteria&insert");
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
        
    $controller = new Kriteria_Model($conn);

    // Mendapatkan id_kelas yang akan dihapus
    $id_kriteria = $_GET['hapus'];

    // Memanggil fungsi hapusKelas
    if ($controller->hapusKriteria($id_kriteria)) {
        header("Location: index.php?page=kriteria&delete");
        exit();
    } else {
        echo "<script>alert('Gagal menghapus data');</script>";
    }
    }
?>
