<?php
class Pengguna_Model {
    private $koneksi;

    public function __construct($conn) {
        $this->koneksi = $conn;
    }
  
    public function updatePassword($passbaru_encrypted, $id_pengguna) {
        // Persiapkan query
        $stmt = $this->koneksi->prepare("UPDATE data_pengguna SET katasandi = ? WHERE id_pengguna = ?");
        
        // Periksa apakah prepare berhasil
        if ($stmt === false) {
            die("Prepare gagal: " . $this->koneksi->error);
        }
        
        // Bind parameter
        if (!$stmt->bind_param("si", $passbaru_encrypted, $id_pengguna)) {
            die("Bind parameter gagal: " . $stmt->error);
        }

        // Eksekusi statement
        if ($stmt->execute()) {
            $stmt->close(); // Tutup statement
            return true; // Jika berhasil mengupdate data
        } else {
            die("Execute gagal: " . $stmt->error); // Tampilkan error jika eksekusi gagal
        }
    }
}

?>