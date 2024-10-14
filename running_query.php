<?php
// Koneksi ke database
$servername = "localhost";
$username = "root"; // Sesuaikan dengan username database Anda
$password = ""; // Sesuaikan dengan password database Anda
$dbname = "db_dying"; // Sesuaikan dengan nama database Anda

// Membuat koneksi
$conn = new mysqli($servername, $username, $password, $dbname);

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Jika form di-submit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $query = $_POST["query"];

    if (!empty($query)) {
        // Jalankan query
        if ($result = $conn->query($query)) {
            // Jika query SELECT
            if (stripos(trim($query), 'SELECT') === 0) {
                echo "<table border='1'>";
                echo "<tr>";

                // Menampilkan header tabel
                $fields = $result->fetch_fields();
                foreach ($fields as $field) {
                    echo "<th>" . $field->name . "</th>";
                }
                echo "</tr>";

                // Menampilkan data
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    foreach ($row as $data) {
                        echo "<td>" . $data . "</td>";
                    }
                    echo "</tr>";
                }
                echo "</table>";
            } else {
                echo "Query berhasil dijalankan.";
            }
        } else {
            echo "Error: " . $conn->error;
        }
    } else {
        echo "Query tidak boleh kosong.";
    }
}

// Tutup koneksi
$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>SQL Query Runner</title>
</head>
<body>
    <h2>Run SQL Query</h2>
    <form method="POST">
        <textarea name="query" rows="5" cols="50" placeholder="Masukkan SQL query di sini"></textarea><br><br>
        <input type="submit" value="Run Query">
    </form>
</body>
</html>
