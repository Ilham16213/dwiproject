<?php
class Bobot_Model {
    private $koneksi;

    public function __construct($conn) {
        $this->koneksi = $conn;
    }

    public function updateBobot($id_bobot, $keterangan) {
        $stmt = $this->koneksi->prepare("UPDATE data_bobot SET status=? WHERE id_bobot=?");
        $stmt->bind_param("si", $keterangan, $id_bobot);
        
        if ($stmt->execute()) {
            return true; // Jika berhasil update data
        } else {
            return false; // Jika gagal update data
        }
    }

    
    public function tambahBobot($bobot1, $bobot2, $bobot3, $bobot4) {
        // Siapkan query untuk update
        $sql = "UPDATE data_bobot SET bobot = ?, `status` = ? WHERE nama = ?";
        $stmt = $this->koneksi->prepare($sql);
    
        // Periksa apakah statement berhasil dipersiapkan
        if ($stmt === false) {
            die("Error preparing statement: " . $this->koneksi->error);
        }
    
        // Bind parameter dan eksekusi query untuk setiap bobot
        $stmt->bind_param("dss", $bobot, $status, $nama);
    
        $bobot = $bobot1;
        $status = 'benefit';
        $nama = '(C1) KESEHATAN TANAMAN';
        if (!$stmt->execute()) {
            echo "Gagal memperbarui data untuk $nama.";
        }
    
        $bobot = $bobot2;
        $status = 'benefit';
        $nama = '(C2) PERTUMBUHAN TANAMAN';
        if (!$stmt->execute()) {
            echo "Gagal memperbarui data untuk $nama.";
        }
    
        $bobot = $bobot3;
        $status = 'benefit';
        $nama = '(C3) WARNA DAUN';
        if (!$stmt->execute()) {
            echo "Gagal memperbarui data untuk $nama.";
        }
    
        $bobot = $bobot4;
        $status = 'benefit';
        $nama = '(C4) KUALITAS AKAR';
        if (!$stmt->execute()) {
            echo "Gagal memperbarui data untuk $nama.";
        }
    
        echo "Data berhasil diperbarui!";
    }
    
}
