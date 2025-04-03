<?php
class User_Model {
    private $koneksi;

    public function __construct($conn) {
        $this->koneksi = $conn;
    }

    public function tambahUser($pengguna, $jabatan, $status, $katasandi, $profil) {
        $stmt = $this->koneksi->prepare("INSERT INTO data_pengguna (pengguna, katasandi, akses, `status`, profil) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $pengguna, $katasandi, $jabatan, $status, $profil);
        
        if ($stmt->execute()) {
            return true; // Jika berhasil memasukkan data
        } else {
            return false; // Jika gagal memasukkan data
        }
    }

    public function hapusUser($id_user) {
        $stmt = $this->koneksi->prepare("DELETE FROM data_pengguna WHERE id_pengguna = ?");
        $stmt->bind_param("i", $id_user);
        
        if ($stmt->execute()) {
            return true; // Jika berhasil menghapus data
        } else {
            return false; // Jika gagal menghapus data
        }
    }
}
?>
