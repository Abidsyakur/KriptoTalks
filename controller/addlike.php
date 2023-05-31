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

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $id_post = $_GET['id'];

    // Periksa apakah user sudah memberikan like sebelumnya
    $query = "SELECT * FROM likes WHERE id_post = '$id_post' AND id_users = '$id_users'";
    $hasil = mysqli_query($conn, $query);

    if (mysqli_num_rows($hasil) == 0) {
        // User belum memberikan like, tambahkan like ke database
        $query = "INSERT INTO likes (id_post, id_users) VALUES ('$id_post', '$id_users')";
        $hasil = mysqli_query($conn, $query);

        if ($hasil) {
            // Like berhasil ditambahkan
            // Update jumlah like pada tabel post
            $query = "UPDATE post SET likes = likes + 1 WHERE id_post = '$id_post'";
            $hasil = mysqli_query($conn, $query);

            // Alihkan kembali ke halaman detail_post
            header("Location: ../view/detail_post.php?id=$id_post");
            exit();
        } else {
            // Terjadi kesalahan saat menambahkan like
            echo "Error: " . mysqli_error($conn);
        }
    } else {
        // User sudah memberikan like sebelumnya
        // Anda dapat melakukan penanganan yang sesuai, misalnya memberikan pesan bahwa user sudah memberikan like sebelumnya
        echo "Anda sudah memberikan like pada postingan ini";
    }
}


?>
