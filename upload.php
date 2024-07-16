<?php
// Menggunakan autoload composer
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;

// Koneksi ke database MySQL
$host = 'localhost'; // Ganti dengan host MySQL Anda
$user = 'root'; // Ganti dengan username MySQL Anda
$pass = 'root'; // Ganti dengan password MySQL Anda
$dbname = 'spreadsheet'; // Ganti dengan nama database Anda

$conn = new mysqli($host, $user, $pass, $dbname);

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi database gagal: " . $conn->connect_error);
}

// Proses upload file Excel
if (isset($_POST['submit'])) {
    $file_excel = $_FILES['file']['tmp_name'];

    try {
        // Load spreadsheet
        $spreadsheet = IOFactory::load($file_excel);
    } catch (\PhpOffice\PhpSpreadsheet\Reader\Exception $e) {
        echo '<a href="/spreadsheet">back</a></br>';
        die('Error loading file: ' . $e->getMessage());
    }

    // Ambil data dari sheet pertama (indeks 0)
    $sheetData = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);

    // Array untuk menyimpan data yang akan dimasukkan
    $dataToInsert = [];

    // Mulai dari baris kedua (indeks 2), menghindari baris header
    foreach ($sheetData as $index => $row) {
        if ($index === 1) { // Skip baris header
            continue;
        }

        // Cek apakah ada data di kolom yang relevan
        if (empty($row['B'])) {
            continue; // Lewati baris jika kolom A kosong
        }

        // Ambil nilai kolom sesuai dengan kolom di Excel
        $nama = $conn->real_escape_string($row['B']);
        $alamat = $conn->real_escape_string(!empty($row['C']) ? $row['C'] : ''); // Jika kolom B kosong, set nilai default

        // Query untuk memeriksa apakah data sudah ada di database
        $checkQuery = "SELECT COUNT(*) as count FROM users WHERE name = '$nama' AND email = '$alamat'";
        $result = $conn->query($checkQuery);

        if ($result) {
            $row = $result->fetch_assoc();
            $count = $row['count'];

            if ($count == 0) {
                // Jika data tidak ada di database, tambahkan ke array untuk dimasukkan
                $dataToInsert[] = "('$nama', '$alamat')";
            } else {
                // Jika data sudah ada, skip
                echo "Data $nama, $alamat sudah ada di database. Dilewati.<br>";
            }
        } else {
            echo "Error: " . $checkQuery . "<br>" . $conn->error;
        }
    }

    // Jika ada data baru untuk dimasukkan, lakukan INSERT
    if (!empty($dataToInsert)) {
        $insertQuery = "INSERT INTO users (name, email) VALUES " . implode(',', $dataToInsert);

        if ($conn->query($insertQuery) !== TRUE) {
            echo "Error: " . $insertQuery . "<br>" . $conn->error;
        } else {
            echo "Data baru berhasil diimpor ke database.";
        }
    } else {
        echo "Tidak ada data baru untuk dimasukkan.";
    }
}

// Tutup koneksi
$conn->close();

?>
<a href="/spreadsheet">back</a>