<?php
class User {
    private $email;
    private $password;

    public function __construct($email, $password) {
        $this->email = $email;
        $this->password = $password;
    }

    public function loginUser() {
        // Kode untuk melakukan validasi login dan verifikasi data pengguna

        //  koneksi ke database
       include '../config/koneksi.php';
        // Memeriksa koneksi database
        if ($conn->connect_error) {
            die("Koneksi ke database gagal: " . $conn->connect_error);
        }

        // Melakukan query untuk mencari pengguna berdasarkan email
        $sql = "SELECT * FROM users WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $this->email);
        $stmt->execute();
        $result = $stmt->get_result();

        // Memeriksa apakah pengguna ditemukan dan password sesuai
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            if ($row['password'] === $this->password) {
                // Menyimpan data pengguna dalam session
                session_start();
                $_SESSION['id'] = $row['id_users'];
                $_SESSION['email'] = $row['email'];
                $_SESSION['nama'] = $row['nama'];

                return true;
            }
        }
        return false;
    }
    // Metode lainnya sesuai kebutuhan
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ambil data dari form login
    $email = $_POST['email'];
    $password = $_POST['password'];

    $user = new User($email, $password);
    if ($user->loginUser()) {
        // Jika login berhasil, redirect ke halaman setelah login
        header("Location: ../view/home.php"); // Ganti dengan halaman setelah login yang sesuai
        exit();
    } else {
        // Jika login gagal, tampilkan pesan error
        $errorMessage = "Email atau password salah. Silakan coba lagi.";
    }
}
?>
