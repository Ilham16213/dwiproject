<?php
class Kriteria_Model {
    private $koneksi;

    public function __construct($conn) {
        $this->koneksi = $conn;
    }

    public function tambahKriteria($id_bobot, $nama_kriteria, $nilai) {
        $stmt = $this->koneksi->prepare("INSERT INTO data_kriteria (id_bobot, nama, nilai) VALUES (?, ?, ?)");
        $stmt->bind_param("isi", $id_bobot, $nama_kriteria, $nilai);
        
        if ($stmt->execute()) {
            return true; // Jika berhasil memasukkan data
        } else {
            return false; // Jika gagal memasukkan data
        }
    }

    public function hapusKriteria($id_kriteria) {
        $stmt = $this->koneksi->prepare("DELETE FROM data_kriteria WHERE id_kriteria = ?");
        $stmt->bind_param("i", $id_kriteria);
        
        if ($stmt->execute()) {
            return true; // Jika berhasil menghapus data
        } else {
            return false; // Jika gagal menghapus data
        }
    }

    public function updateKelas($id_kelas, $nama_kelas, $wali_kelas) {
        $stmt = $this->koneksi->prepare("UPDATE data_kelas SET nama_kelas = ?, wali_kelas = ? WHERE id_kelas = ?");
        $stmt->bind_param("ssi", $nama_kelas, $wali_kelas, $id_kelas);
        
        if ($stmt->execute()) {
            return true; // Jika berhasil mengupdate data
        } else {
            return false; // Jika gagal mengupdate data
        }
    }
}
?>
