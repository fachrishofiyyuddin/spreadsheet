<?php
// Koneksi ke database (ganti dengan detail koneksi Anda)
$host = 'localhost';
$dbname = 'spreadsheet'; // Ini seharusnya nama database, bukan nama pengguna
$username = 'root';
$password = 'root';

// Buat koneksi
$conn = mysqli_connect($host, $username, $password, $dbname);

// Periksa koneksi
if (!$conn) {
    die('Koneksi database gagal: ' . mysqli_connect_error());
}

// Query untuk mengambil data pengguna
$query = "SELECT id_user, name, email FROM users";
$result = mysqli_query($conn, $query);

if (!$result) {
    die('Error saat menjalankan query: ' . mysqli_error($conn));
}

// Buat array untuk menyimpan data pengguna
$users = array();

// Ambil hasil query ke dalam array asosiatif
while ($row = mysqli_fetch_assoc($result)) {
    $users[] = $row;
}

// Set header untuk memastikan klien tahu bahwa ini adalah JSON
header('Content-Type: application/json');

// Keluarkan data sebagai JSON
echo json_encode($users);

// Tutup koneksi
mysqli_close($conn);
