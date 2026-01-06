<?php
$conn = new mysqli('localhost', 'root', '', 'kelaskita2.0');
if ($conn->connect_error) {
    die('Connection failed: ' . $conn->connect_error);
}

$result = $conn->query('SELECT id_mp, nama_metode, nomor_rekening, nama_pemilik FROM metode_pembayaran LIMIT 10');
echo "Payment Method Details:\n";
while($row = $result->fetch_assoc()) {
    echo $row['id_mp'] . ' | ' . $row['nama_metode'] . ' | ' . ($row['nomor_rekening'] ? substr($row['nomor_rekening'], 0, 8) . '...' : 'NULL') . ' | ' . $row['nama_pemilik'] . "\n";
}

$conn->close();
?>
