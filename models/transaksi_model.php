<?php
class Transaksi_Model {
    private $koneksi;

    public function __construct($conn) {
        $this->koneksi = $conn;
    }

    public function tambahTransaksi($alternatif, $c1, $c2, $c3, $c4, $tanggal_input) {
        $stmt = $this->koneksi->prepare("INSERT INTO data_alternatif (nama_alternatif, c1, c2, c3, c4, tanggal_input) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssss", $alternatif, $c1, $c2, $c3, $c4, $tanggal_input);
        
        if ($stmt->execute()) {
            return true; // Jika berhasil memasukkan data
        } else {
            return false; // Jika gagal memasukkan data
        }
    }
    
    public function hapussemuaTransaksi($id_alternatif) {
        $stmt = $this->koneksi->prepare("DELETE FROM data_alternatif");
        $stmt->execute();
        
        if ($stmt->execute()) {
            return true; // Jika berhasil menghapus data
        } else {
            return false; // Jika gagal menghapus data
        }
    }

    public function hapusTransaksi($id_alternatif) {
        $stmt = $this->koneksi->prepare("DELETE FROM data_alternatif WHERE id_alternatif = ?");
        $stmt->bind_param("i", $id_alternatif);
        
        if ($stmt->execute()) {
            return true; // Jika berhasil menghapus data
        } else {
            return false; // Jika gagal menghapus data
        }
    }
}
?>
