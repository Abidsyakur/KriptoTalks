<?php
include_once('../config/koneksi.php');
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET['id_post'])) {
        $id_post = $_GET['id_post'];
        // Panggil fungsi deletePost dengan parameter $id_post
        if (deletePost($id_post)) {
            echo '<script>alert("Berhasil Dihapus");</script>';
            echo "<script>window.location.href = '../view/home.php';</script>";
        } else {
            echo '<script>alert("Bukan Owner Post !!");</script>';
            echo "<script>window.location.href = '../view/home.php';</script>";
            
        }
    } else {
        echo '<script>alert("id post missing  !!");</script>';
        echo "<script>window.location.href = '../view/home.php';</script>";
    }
} else {
    echo "Invalid request.";
}

function deletePost($id_post) {
    global $conn;

    // Hapus post
    $query = "DELETE FROM post WHERE id_post = '$id_post'";
    $result = mysqli_query($conn, $query);
    if (!$result) {
        return false;
    }

    // Hapus bookmarks
    $query = "DELETE FROM bookmarks WHERE id_post = '$id_post'";
    $result = mysqli_query($conn, $query);
    if (!$result) {
        return false;
    }

    // Hapus komentar
    $query = "DELETE FROM komentar WHERE id_post = '$id_post'";
    $result = mysqli_query($conn, $query);
    if (!$result) {
        return false;
    }

    // Hapus likes
    $query = "DELETE FROM likes WHERE id_post = '$id_post'";
    $result = mysqli_query($conn, $query);
    if (!$result) {
        return false;
    }

    // Hapus kategori yang tidak terhubung dengan post lain
    $query = "DELETE FROM kategori WHERE id_kategori NOT IN (SELECT id_kategori FROM post)";
    $result = mysqli_query($conn, $query);
    if (!$result) {
        return false;
    }

    return true;
}
?>
