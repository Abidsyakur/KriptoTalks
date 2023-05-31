<?php
function registerUser($email,$username, $nama, $password, $foto) {
    $host = 'localhost';
    $dbname = 'kt';
    $dbusername = 'root';
    $dbpassword = '';

    try {
        $conn = new PDO("mysql:host=$host;dbname=$dbname", $dbusername, $dbpassword);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Memeriksa apakah foto diunggah atau tidak
        if ($foto['name'] !== '' && $foto['error'] === 0) {
            // Jika ada foto yang diunggah
            $targetDir = '../model/upload/profile/';
            $targetFile = $targetDir . basename($foto['name']);
            move_uploaded_file($foto['tmp_name'], $targetFile);
            $fotoName = $foto['name'];
        } else {
            // Jika tidak ada foto yang diunggah, menggunakan foto default
            $fotoName = 'foto_default.png';
        }

        // Menyiapkan query untuk memasukkan data pengguna ke dalam tabel users
        $stmt = $conn->prepare("INSERT INTO users (nama,username, email, password, foto) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$nama, $username, $email, $password, $fotoName]);

        // Menutup koneksi ke database
        $conn = null;

        // Kembalikan pesan atau hasil operasi, misalnya:
        return 'Pendaftaran berhasil!';
    } catch (PDOException $e) {
        return 'Terjadi kesalahan saat mendaftar: ' . $e->getMessage();
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $username = $_POST['username'];
    $nama = $_POST['nama'];
    $password = $_POST['password'];
    $foto = $_FILES['foto'];
    
    $result = registerUser($email, $username, $nama, $password, $foto);
    echo $result;
    echo '<script>alert("Pendaftaran berhasil!");</script>';}
    echo "<script>window.location.href = '../login.php';</script>";

?>
