<?php
// Koneksi ke database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "bangdwi";

$conn = new mysqli($servername, $username, $password, $dbname);

// Periksa koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Proses perubahan status
if (isset($_POST['id_pengguna'])) {
    $id_pengguna = $_POST['id_pengguna'];
    $status_baru = $_POST['status'];
    
    $sql = "UPDATE data_pengguna SET status = '$status_baru' WHERE id_pengguna = $id_pengguna";
    
    if ($conn->query($sql) === TRUE) {
        echo "Status berhasil diubah.";
    } else {
        echo "Error: " . $conn->error;
    }
    exit;
}

// Ambil data pengguna dari database
$sql = "SELECT * FROM data_pengguna";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Status Pengguna</title>
</head>
<body>
    <table border="1" cellpadding="10" cellspacing="0" style="width: 100%; text-align: center;">
        <thead>
            <tr>
                <th>ID</th>
                <th>Pengguna</th>
                <th>Akses</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php while($row = $result->fetch_assoc()) { ?>
                <tr>
                    <td><?php echo $row['id_pengguna']; ?></td>
                    <td><?php echo $row['pengguna']; ?></td>
                    <td><?php echo $row['akses']; ?></td>
                    <td>
                        <div style="position: relative; display: inline-block; width: 40px; height: 20px;">
                            <input 
                                type="checkbox" 
                                <?php echo $row['status'] == 'aktif' ? 'checked' : ''; ?>
                                style="
                                    width: 40px; 
                                    height: 20px; 
                                    background-color: <?php echo $row['status'] == 'aktif' ? '#15a362' : '#f00'; ?>; 
                                    border-radius: 20px; 
                                    border: none; 
                                    appearance: none; 
                                    cursor: pointer; 
                                    position: relative; 
                                    transition: background-color 0.3s ease-in-out;
                                    outline: none;"
                                onclick="toggleStatus(<?php echo $row['id_pengguna']; ?>, this)">
                            <span 
                                style="
                                    position: absolute; 
                                    top: 5px; 
                                    left: 5px; 
                                    width: 16px; 
                                    height: 16px; 
                                    background-color: white; 
                                    border-radius: 50%; 
                                    transition: transform 0.3s ease-in-out;
                                    transform: <?php echo $row['status'] == 'aktif' ? 'translateX(20px)' : 'translateX(0)'; ?>;
                                    pointer-events: none;">
                            </span>
                        </div>
                    </td>
                    
                    <td>
					    <a type='button' class='btn btn-danger btn-sm' href='index.php?page=pengguna&hapus=" .  <?php echo $row['id_pengguna']; ?> . "' onclick='return confirmDelete()'>Hapus</a>
					</td>
                </tr>
            <?php } ?>
        </tbody>
    </table>

    <script>
        function toggleStatus(id_pengguna, checkbox) {
            const newStatus = checkbox.checked ? 'aktif' : 'nonaktif';
            const xhr = new XMLHttpRequest();
            xhr.open("POST", "", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.onreadystatechange = function() {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    checkbox.style.backgroundColor = newStatus === 'aktif' ? '#15a362' : '#f00';
                    checkbox.nextElementSibling.style.transform = newStatus === 'aktif' ? 'translateX(20px)' : 'translateX(0)';
                }
            };
            xhr.send("id_pengguna=" + id_pengguna + "&status=" + newStatus);
        }
    </script>
</body>
</html>

<?php
$conn->close();
?>
