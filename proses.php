<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ambil data dari POST request
    $bobot1 = $_POST['bobot1'] ?? '';
    $bobot2 = $_POST['bobot2'] ?? '';
    $bobot3 = $_POST['bobot3'] ?? '';
    $bobot4 = $_POST['bobot4'] ?? '';

    // Tampilkan data
    echo "<h2>Data yang Dikirim</h2>";
    echo "<table class='table table-bordered'>";
    echo "<thead><tr><th>Kriteria</th><th>Bobot</th></tr></thead><tbody>";
    echo "<tr><td>Kriteria 1</td><td>" . htmlspecialchars($bobot1) . "</td></tr>";
    echo "<tr><td>Kriteria 2</td><td>" . htmlspecialchars($bobot2) . "</td></tr>";
    echo "<tr><td>Kriteria 3</td><td>" . htmlspecialchars($bobot3) . "</td></tr>";
    echo "<tr><td>Kriteria 4</td><td>" . htmlspecialchars($bobot4) . "</td></tr>";
    echo "</tbody></table>";
}
?>
