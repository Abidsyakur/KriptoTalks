<?php
include '../config/koneksi.php';
session_start();

class Postingan {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function tambahKategori($namaKategori) {
        $sql = "INSERT INTO kategori (nama_kategori) VALUES ('$namaKategori')";
        $result = mysqli_query($this->conn, $sql);
        if ($result){
            // Kategori berhasil ditambahkan
            return true;
        } else {
            // Gagal menambahkan kategori
            return false;
        }
    }

    public function tambahPostingan($idUsers, $idkategori, $judul, $konten, $gambar, $tanggalPost) {
        $sql = "INSERT INTO post (id_users, id_kategori, judul, konten, gambar, tgl_post) VALUES ('$idUsers', '$idkategori', '$judul', '$konten', '$gambar', '$tanggalPost')";
        $result = mysqli_query($this->conn, $sql);
        if ($result) {
            // Postingan berhasil ditambahkan
            return true;
        } else {
            // Gagal menambahkan postingan
            return false;
        }
    }
}

// Contoh penggunaan
// Buat objek Postingan dengan menyediakan koneksi database
$postingan = new Postingan($conn);

// Ambil data dari form
$idUsers = $_SESSION['id'];
$kategori = $_POST['kategori'];
$judul = $_POST['judul'];
$konten = $_POST['konten'];
$gambar = $_FILES['gambar']['name'];
$tanggalPost = date('Y-m-d H:i:s'); // Mendapatkan waktu atau tanggal saat ini

// Query untuk mendapatkan id_kategori dari tabel kategori

// Panggil metode tambahKategori untuk menambahkan kategori jika belum ada
if (!$postingan->tambahKategori($kategori)) {
    // Gagal menambahkan kategori
    echo "Gagal menambahkan kategori.";
    exit;
}

$query = "SELECT * FROM kategori WHERE nama_kategori = '$kategori'";
// Lakukan query ke database dan ambil id_kategori
// ...

$result = mysqli_query($conn, $query);
if ($result) {
    // Periksa apakah query mengembalikan baris hasil
    if (mysqli_num_rows($result) > 0) {
        // Ambil id_kategori dari hasil query
        $row = mysqli_fetch_assoc($result);
        $id_kategori = $row['id_kategori'];

        // Lakukan upload gambar dan tambah postingan
        $gambarTmp = $_FILES['gambar']['tmp_name'];
        $uploadPath = '../model/upload/post/' . $gambar;
        move_uploaded_file($gambarTmp, $uploadPath);

        if ($postingan->tambahPostingan($idUsers, $id_kategori, $judul, $konten, $gambar, $tanggalPost)) {
            // Postingan berhasil ditambahkan
            echo "Postingan berhasil ditambahkan.";
            echo "<script>window.location.href = '../view/home.php';</script>";
        } else {
            // Gagal menambahkan postingan
            echo "Gagal menambahkan postingan."; 
        }
    } else {
        // Tidak ada baris hasil yang sesuai dengan kondisi
        echo "Kategori tidak ditemukan.";
    }
} else {
    // Gagal menjalankan query
    echo "Gagal menjalankan query.";
}

// ...
?>
