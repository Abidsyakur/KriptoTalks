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





?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Bootstrap demo</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
  <link rel="stylesheet" href="../model/asset/css/style2.css">
</head>
<style>
  body{
    overflow-y: auto;

  }
  .content {
    flex-grow: 1;
    padding: 20px;
    overflow-y: auto;
    display: flex;
    flex-direction: column;
    align-items: center;
    margin-left: 2rem;
  }

  .left-sidebar {
    width: 25%;
    background-color: black;
    color: white;
    padding: 10px;
  }

  .center-content {
    width: 50%;
    padding: 10px;
  }

  .right-sidebar {
    width: 25%;
    position: fixed;
    background-color: lightgray;
    padding: 10px;
    display: flex;
    justify-content: end;
  }
  .post {
    background-color: #1e1e1e;
    padding: 20px;
    border-radius: 25px;
    margin-bottom: 2rem;
  }
  .post a{
    cursor: pointer;
    text-decoration: none;
    color:white
  }
</style>
<body>
  <div class="container">
    <div class="row">
    <div class="col-md-3 left-sidebar">
  <!-- Bagian kiri -->
  <div class="d-flex flex-column justify-content-end">
    <div class="profil  d-flex align-items-center" style="top:0;bottom:0;margin-top:40rem;position:fixed">
      <div class="mr-3">
        <img src="../model/upload/profile/<?=$result['foto']?>" width="50" height="50" alt="foto" class="rounded-circle">
      </div>
      <div class="mx-3">
        <h4 class="text-white m-0"><?=$result['nama']?></h4>
        <p class="text-white m-0">@<?=$result['username']?></p>
      </div>
    </div>

    <div class="mt-2">
      <!-- Konten elemen bookmarks -->
      <div class="bookmarks">
          <a href="bookmarks.php" style="text-decoration:none;color:white;cursor:pointer;">
          <div class="row">
            <img src="../model/asset/img/bookmark.png" alt="">Bookmarks   
          </div>
        </a>
        </div>
    </div>
    <div class="mt-2">
      <!-- Konten elemen profile -->
      <div class="profile">
        <div class="row">
          <img src="../model/asset/img/profile.png" alt="">Profile
        </div>
      </div>
    </div>
  </div>
  <a href="home.php">
    <div class="logo">
      <img src="../model/asset/img/logo.png" alt="logo">
    </div>
  </a>
</div>

      <div class="col-md-6 center-content">


        <!-- Bagian tengah -->
        <div class="content">
          <h2>Daftar postingan</h2>
                  <!-- bagian post -->  
                  <?php
        $query = "SELECT bookmarks.id_post, bookmarks.id_users, users.username AS bookmarked_by, post.id_users AS post_owner, post.judul, post.konten, post.gambar
          FROM bookmarks
          INNER JOIN users ON bookmarks.id_users = users.id_users
          INNER JOIN post ON bookmarks.id_post = post.id_post
          WHERE bookmarks.id_users = '$id_users'
          ORDER BY bookmarks.id_bookmarks DESC";
        $hasil = mysqli_query($conn, $query);

        foreach ($hasil as $row) {
        $id_post = $row['id_post'];
        $post_owner = $row['post_owner'];

        // Query untuk mendapatkan informasi pembuat post
        $query_owner = "SELECT * FROM users WHERE id_users = '$post_owner'";
        $hasil_owner = mysqli_query($conn, $query_owner);
        $row_owner = mysqli_fetch_assoc($hasil_owner);

    // Menampilkan informasi postingan dari bookmarks
    ?>
    <div class="post">
        <div class="prof mb-2">
            <img src="../model/upload/profile/<?= $row_owner['foto'] ?>" width="50" height="50" alt="foto" class="rounded-circle">
            <b><?= $row_owner['nama'] ?></b><span style="color: white; opacity: 0.5">@<?= $row_owner['username'] ?></span>
        </div>
        <a href="detail_post.php?id=<?= $id_post ?>">
            <h3><?= $row['judul'] ?></h3>
            <p><?= $row['konten'] ?></p>
            <img src="../model/upload/post/<?= $row['gambar'] ?>" width="500px" alt="foto">
        </a>
    </div>
<?php } ?>

          <!-- Tambahkan postingan lainnya -->
        </div>
      </div>

    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
</body>
</html>
