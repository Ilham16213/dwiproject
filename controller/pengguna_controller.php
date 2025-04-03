<?php 
    require_once 'models/pengguna_model.php';
    

        if (isset($_POST['update'])) {
            $controller = new Pengguna_Model($conn);

            // Ambil data dari form
            $id_pengguna = $_SESSION['id']; // ID pengguna dari sesi yang aktif
            $passlama = md5($_POST['passlama']); // Enkripsi kata sandi lama
            $passbaru = $_POST['passbaru'];
            $passkonfirmasi = $_POST['passkonfirmasi'];
            
            // Cek apakah kata sandi lama cocok
            $query = "SELECT katasandi FROM data_pengguna WHERE id_pengguna = '$id_pengguna'";
            $result = mysqli_query($conn, $query);
            $user = mysqli_fetch_assoc($result);
            
            if ($user['katasandi'] != $passlama) {
                header("Location: index.php?page=setting&erroroldpass");
                exit;
            }
            
            // Cek apakah kata sandi baru dan konfirmasi sama
            if ($passbaru != $passkonfirmasi) {
                header("Location: index.php?page=setting&errorconfirm");
                exit;
            }
            
            // Enkripsi kata sandi baru
            $passbaru_encrypted = md5($passbaru);
            
            if ($controller->updatePassword($passbaru_encrypted, $id_pengguna)) {
                header("Location: index.php?page=setting&update");
                exit();
            } else {
                echo "<script>alert('Gagal mengubah katasandi');</script>";
            }
        }


        if (isset($_POST['upload'])) {
            // Pastikan session sudah dimulai (hanya jika session belum dimulai sebelumnya)
            if (session_status() == PHP_SESSION_NONE) {
                session_start();
            }

            // Inisialisasi kode
            $id = $_SESSION['id']; // ID pengguna dari session
            $kode = uniqid(); // Generate kode unik

            // Query untuk mendapatkan data profil
            $sql = "SELECT * FROM data_pengguna WHERE id_pengguna = $id";

            // Jalankan query
            $result = $conn->query($sql);

            // Periksa apakah ada data yang ditemukan
            if ($result->num_rows > 0) {
                // Ambil data pengguna
                $row = $result->fetch_assoc();
                $namaprofil = $row['profil'];

                // Tentukan lokasi folder tempat gambar disimpan
                $path = 'assets/images/profiles/' . $namaprofil;


                // Cek apakah nama file bukan 'default.jpg' dan file tersebut ada di folder
                if ($namaprofil !== 'default.jpg' && file_exists($path)) {
                    if (unlink($path)) {
                    } 
                }
            } 



            // Direktori tempat gambar akan disimpan
            $target_dir = "assets/images/profiles/";
        
            // Ekstensi file gambar asli
            $imageFileType = strtolower(pathinfo($_FILES["gambar"]["name"], PATHINFO_EXTENSION));
        
            // Nama file JPG baru berdasarkan nomor yang diisi
            $new_file_name = "gambar-" . $kode . ".jpg";
            $target_file = $target_dir . $new_file_name;
                    
            // Query update nama profil
            $sql = "UPDATE data_pengguna SET profil = '$new_file_name' WHERE id_pengguna = $id";

            // Jalankan query
            if ($conn->query($sql) === TRUE) {
                echo "Data berhasil diupdate.";
            } else {
                echo "Error: " . $conn->error;
            }


            // Cek apakah file yang diupload adalah gambar
            $check = getimagesize($_FILES["gambar"]["tmp_name"]);
            if ($check === false) {
                echo "<script>alert('File yang diupload bukan gambar.');</script>";
                exit;
            }
        
            // Cek ukuran file (maks 2MB)
            if ($_FILES["gambar"]["size"] > 2000000) {            
                echo "<script>alert('Maaf, file terlalu besar.');</script>";
                exit;
            }
        
            // Hanya izinkan format gambar tertentu
            $allowed_types = ['jpg', 'jpeg', 'png', 'gif', 'bmp'];
        
            if (!in_array($imageFileType, $allowed_types)) {
                echo "Maaf, hanya file JPG, JPEG, PNG, GIF, dan BMP yang diizinkan.";
                exit;
            }
        
            // Jika semua cek berhasil, upload file sementara
            if (move_uploaded_file($_FILES["gambar"]["tmp_name"], $_FILES["gambar"]["tmp_name"])) {
                echo "File berhasil diupload.<br>";
                
                // Konversi dan crop gambar ke JPG
                cropAndConvertToJPG($_FILES["gambar"]["tmp_name"], $target_file, $imageFileType);
                echo "Gambar berhasil dikonversi ke format JPG dan dicrop 1:1.";
            } else {
                echo "Maaf, terjadi kesalahan saat mengupload file.";
            }
        }
        
        // Fungsi untuk crop 1:1 dan konversi gambar ke JPG
        function cropAndConvertToJPG($file_path, $output_path, $imageFileType) {
            // Buat gambar dari file yang diupload sesuai tipe
            switch ($imageFileType) {
                case 'png':
                    $image = imagecreatefrompng($file_path);
                    break;
                case 'gif':
                    $image = imagecreatefromgif($file_path);
                    break;
                case 'bmp':
                    $image = imagecreatefrombmp($file_path);
                    break;
                case 'jpg':
                case 'jpeg':
                    $image = imagecreatefromjpeg($file_path);
                    break;
                default:
                    echo "Format gambar tidak didukung.";
                    return;
            }
        
            // Mendapatkan dimensi asli gambar
            $width = imagesx($image);
            $height = imagesy($image);
        
            // Tentukan ukuran untuk crop (1:1)
            $min_dim = min($width, $height);
            $x_offset = ($width - $min_dim) / 2;
            $y_offset = ($height - $min_dim) / 2;
        
            // Crop gambar menjadi kotak (1:1)
            $cropped_image = imagecrop($image, ['x' => $x_offset, 'y' => $y_offset, 'width' => $min_dim, 'height' => $min_dim]);
            
            if ($cropped_image !== false) {
                // Simpan gambar yang telah dicrop sebagai JPG
                imagejpeg($cropped_image, $output_path, 100); // 100 adalah kualitas JPG (maksimal)
                imagedestroy($cropped_image);
            }
        
            // Hapus resource gambar asli
            imagedestroy($image);
        
            // Hapus cache dan refresh halaman
            header("Cache-Control: no-cache, no-store, must-revalidate"); // HTTP 1.1.
            header("Pragma: no-cache"); // HTTP 1.0.
            header("Expires: 0"); // Proxies.
            header("Location: index.php?page=setting&true");
            exit;
        }
        
?>