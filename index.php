<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Upload Excel/CSV to MySQL</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.6.0/css/bootstrap.min.css">
</head>

<body>

    <div class="container mt-4">
        <form action="upload.php" method="post" enctype="multipart/form-data" class="mb-5">
            <input type="file" name="file" accept=".csv, .xlsx, .xls">
            <button type="submit" name="submit">Upload File</button>
        </form>

        <h2>Data Table</h2>
        <table id="dataTable" class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                </tr>
            </thead>
            <tbody>
                <!-- Data will be dynamically added here -->
            </tbody>
        </table>
    </div>
</body>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

<script>
    $(document).ready(function() {
        // Fungsi untuk memperbarui tabel
        function refreshTable() {
            $.ajax({
                url: 'api.php', // Ganti dengan URL endpoint API Anda
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    console.log(data);
                    // Cek apakah ada data baru yang perlu ditampilkan
                    if (data.length > 0) {

                        // Bersihkan tabel
                        $('#dataTable tbody').empty();

                        // Iterasi data dan tambahkan baris baru ke tabel
                        $.each(data, function(index, item) {
                            $('#dataTable tbody').append(
                                '<tr>' +
                                '<td>' + item.id_user + '</td>' +
                                '<td>' + item.name + '</td>' +
                                '<td>' + item.email + '</td>' +
                                '</tr>'
                            );
                        });
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log('Error: ' + textStatus + ' - ' + errorThrown);
                }
            });
        }

        // Panggil refreshTable() pertama kali saat halaman dimuat
        refreshTable(); // Ubah angka 5000 sesuai kebutuhan (dalam milidetik)
    });
</script>

</html>