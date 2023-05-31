<?php
session_start();
include_once('../config/koneksi.php');

if (isset($_GET['user_id'])) {
    $following_id = $_SESSION['id']; // ID pengguna yang melakukan tindakan follow
    $followers_id = $_GET['user_id']; // ID pengguna yang akan diikuti

    // Periksa apakah pengguna yang melakukan tindakan follow dan pengguna yang akan diikuti sudah terhubung sebagai followers sebelumnya
    $query = "SELECT * FROM followers WHERE following_id_users = '$following_id' AND followers_id_users = '$followers_id'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        // Jika sudah terhubung sebagai followers, hapus hubungan tersebut (berhenti mengikuti)
        $deleteQuery = "DELETE FROM followers WHERE following_id_users = '$following_id' AND followers_id_users = '$followers_id'";
        mysqli_query($conn, $deleteQuery);
        echo "Anda telah berhenti mengikuti pengguna ini.";
    } else {
        // Jika belum terhubung sebagai followers, tambahkan hubungan tersebut (mulai mengikuti)
        $insertQuery = "INSERT INTO followers (following_id_users, followers_id_users) VALUES ('$following_id', '$followers_id')";
        mysqli_query($conn, $insertQuery);
        echo "Anda telah mulai mengikuti pengguna ini.";
    }
} else {
    echo "ID pengguna tidak ditemukan.";
}
?>
