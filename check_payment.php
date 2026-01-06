<?php
$conn = new mysqli('localhost', 'root', '', 'kelaskita2.0');
if ($conn->connect_error) {
    die('Connection failed: ' . $conn->connect_error);
}

$result = $conn->query('SELECT id_mp, nama_metode, is_active FROM metode_pembayaran WHERE is_active = 1');
echo "Active Payment Methods:\n";
while($row = $result->fetch_assoc()) {
    echo $row['id_mp'] . ' | ' . $row['nama_metode'] . ' | ' . $row['is_active'] . "\n";
}

echo "\n\nAll Payment Methods:\n";
$result = $conn->query('SELECT id_mp, nama_metode, is_active FROM metode_pembayaran');
while($row = $result->fetch_assoc()) {
    echo $row['id_mp'] . ' | ' . $row['nama_metode'] . ' | ' . ($row['is_active'] ? 'YES' : 'NO') . "\n";
}

$conn->close();
?>
