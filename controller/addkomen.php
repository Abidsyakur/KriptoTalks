<?php
include_once('../config/koneksi.php');
session_start();

if (isset($_SESSION['id'])) {
    $id_users = $_SESSION['id'];
    $data = mysqli_query($conn, "SELECT * FROM users WHERE id_users = '$id_users' ");
    $result = mysqli_fetch_array($data);
} else {  
    mysqli_error($conn);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_post = $_POST['id_post'];
    $id_users = $_SESSION['id'];
    $konten_komentar = $_POST['konten_komentar'];

    // Proses penyimpanan komentar ke database
    $query = "INSERT INTO komentar (id_post, id_users, konten_komentar) VALUES ('$id_post', '$id_users', '$konten_komentar')";
    $result = mysqli_query($conn, $query);

    if ($result) {
        // Komentar berhasil ditambahkan, alihkan ke halaman detail_post
        header("Location: ../view/detail_post.php?id=$id_post");
        exit();
    } else {
        // Terjadi kesalahan saat menambahkan komentar, tampilkan pesan error
        echo "Error: " . mysqli_error($conn);
    }
}
?>
