<?php
class Database {
    private $conn;

    public function __construct($host, $dbname, $dbusername, $dbpassword) {
        try {
            $this->conn = new PDO("mysql:host=$host;dbname=$dbname", $dbusername, $dbpassword);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo "Koneksi database gagal: " . $e->getMessage();
            exit();
        }
    }

    public function updateUser($id_users, $email, $username, $nama, $password, $foto) {
        try {
            // Memeriksa apakah foto diunggah atau tidak
            if ($foto['name'] !== '' && $foto['error'] === 0) {
                // Jika ada foto yang diunggah
                $targetDir = '../model/upload/profile/';
                $targetFile = $targetDir . basename($foto['name']);
                move_uploaded_file($foto['tmp_name'], $targetFile);
                $fotoName = $foto['name'];
            } else {
                // Jika tidak ada foto yang diunggah, menggunakan foto yang ada sebelumnya
                $stmt = $this->conn->prepare("SELECT foto FROM users WHERE id_users = ?");
                $stmt->execute([$id_users]);
                $result = $stmt->fetch(PDO::FETCH_ASSOC);
                $fotoName = $result['foto'];
            }

            // Menyiapkan query untuk mengupdate data pengguna dalam tabel users
            $stmt = $this->conn->prepare("UPDATE users SET email = ?, username = ?, nama = ?, password = ?, foto = ? WHERE id_users = ?");
            $stmt->execute([$email, $username, $nama, $password, $fotoName, $id_users]);

            // Kembalikan pesan atau hasil operasi, misalnya:
            return 'Data pengguna berhasil diperbarui!';
        } catch (PDOException $e) {
            return 'Terjadi kesalahan saat memperbarui data pengguna: ' . $e->getMessage();
        }
    }

    public function closeConnection() {
        $this->conn = null;
    }
}

// Contoh penggunaan
$host = 'localhost';
$dbname = 'kt';
$dbusername = 'root';
$dbpassword = '';

$database = new Database($host, $dbname, $dbusername, $dbpassword);
session_start();

$id_users = $_SESSION['id'];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $username = $_POST['username'];
    $nama = $_POST['nama'];
    $password = $_POST['password'];
    $foto = $_FILES['foto'];
    
    $result = $database->updateUser($id_users, $email, $username, $nama, $password, $foto);
    echo $result;
    echo '<script>alert("Data pengguna berhasil diperbarui!");</script>';
    echo "<script>window.location.href = '../view/profile.php';</script>";
}

$database->closeConnection();

?>