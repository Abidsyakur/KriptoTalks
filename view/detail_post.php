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
// Query untuk mengambil data post
$id_post = $_GET['id'];

$query = "SELECT * FROM post INNER JOIN users ON post.id_users = users.id_users WHERE post.id_post = '$id_post'";
$hasil = mysqli_query($conn, $query);

function countLikes($id_post) {
    include('../config/koneksi.php');
    // Lakukan query untuk menghitung jumlah likes berdasarkan id_post
    $query = "SELECT COUNT(*) AS total_likes FROM likes WHERE id_post = '$id_post'";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);
    return $row['total_likes'];
}
function countBookmarks($id_post) {
    include('../config/koneksi.php');
    // Lakukan query untuk menghitung jumlah likes berdasarkan id_post
    $query = "SELECT COUNT(*) AS total_book FROM bookmarks WHERE id_post = '$id_post'";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);
    return $row['total_book'];
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
        overflow-y: scroll;
    }
  .content {
    flex-grow: 1;
    padding: 20px;
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
 
    .like-button {
        display: inline-block;
        padding: 5px 10px;
        color: #fff;
        text-decoration: none;
        border-radius: 5px;
    }

    .like-button:hover {
        background-color: red;
    }
    .bookmark-button {
  display: inline-block;
  padding: 5px 10px;
  color: #fff;
  text-decoration: none;
  border-radius: 5px;
}

.bookmark-button:hover {
  background-color: #007bff; /* Ganti dengan warna latar belakang yang diinginkan saat dihover */
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
              <a href="profile.php" style="text-decoration:none;color:white;cursor:pointer;">
                <div class="row">
                  <img src="../model/asset/img/profile.png" alt="">Profile
                </div>
              </a>
            </div>
          </div>
        </div>
        <div class="logo">
          <a href="home.php">  
            <img src="../model/asset/img/logo.png" alt="logo">
          </a>
        </div>
      </div>
      <div class="col-md-6 center-content">
        <!-- Bagian tengah -->
        <div class="content">
          <h2>Daftar postingan</h2>
          <!-- bagian post -->
          <?php foreach($hasil as $row): ?>
            <div class="post">
              <div class="prof mb-2">
                <img src="../model/upload/profile/<?=$row['foto']?>" width="50" height="50" alt="foto" class="rounded-circle">
                <b><?=$row['nama']?></b><span style="color: white;opacity:0.5">@<?=$row['username']?></span>
              </div>
              <a href="detail_post.php?id=<?=$row['id_post']?>">
                <h3><?=$row['judul']?></h3>
                <p><?=$row['konten']?></p>
                <img src="../model/upload/post/<?=$row['gambar']?>" width="500px" alt="foto">
              </a>
              <div class="like">
                <a href="../controller/addlike.php?id=<?=$row['id_post']?>" class="like-button"><svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor" class="bi bi-heart-fill" viewBox="0 0 16 16">
                  <path fill-rule="evenodd" d="M8 1.314C12.438-3.248 23.534 4.735 8 15-7.534 4.736 3.562-3.248 8 1.314z"/>
                </svg></a>
                <?=countLikes($row['id_post'])?>
                <a href="../controller/addbookmark.php?id=<?=$row['id_post']?>" class="bookmark-button">
                  <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor" class="bi bi-bookmark-fill" viewBox="0 0 16 16">
                    <path d="M2 2v13.5a.5.5 0 0 0 .74.439L8 13.069l5.26 2.87A.5.5 0 0 0 14 15.5V2a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2z"/>
                  </svg>
                  <?=countBookmarks($row['id_post'])?>
                </a>
              </div>
              <a href="../controller/delpost.php?id_post=<?=$row['id_post']?>"> <button type="button" class="btn btn-dark"style="float:right;top:0;margin-top:4px;">Delete</button></a>

                <!-- Tombol Like -->
                
                <!-- Komentar self -->
                <div class="input-area">
                  <div class="profile-picture">
                  <img src="../model/upload/profile/<?=$result['foto']?>" width="50" height="50" alt="foto" class="rounded-circle">
                </div>
                <div class="input-wrapper">
                  <form action="../controller/addkomen.php" method="POST" enctype="multipart/form-data">
                    <textarea placeholder="Apa yang sedang Anda pikirkan?" id="konten_komentar" name="konten_komentar"></textarea>
                  </div> 
                  <input type="hidden" name="id_post" value="<?=$row['id_post']?>">
                  <button class="post-button" type="submit">Post</button>
                </form>
              </div>
              <!-- Komentar lainnya -->
              <?php
              $id_post = $row['id_post'];
              $query_komentar = "SELECT * FROM komentar INNER JOIN users ON komentar.id_users = users.id_users WHERE komentar.id_post = '$id_post'";
              $hasil_komentar = mysqli_query($conn, $query_komentar);
              ?>
              <?php foreach($hasil_komentar as $komentar): ?>
                <div class="komen">
                  <img src="../model/upload/profile/<?=$komentar['foto']?>" width="50" height="50" alt="foto" class="rounded-circle">
                  <b><?=$komentar['nama']?></b><span style="color: white;opacity:0.5">@<?=$komentar['username']?></span>
                  <p><?=$komentar['konten_komentar']?></p>
                </div>
              <?php endforeach; ?>
            </div>
          <?php endforeach; ?>
          

          <!-- Tambahkan postingan lainnya -->
            
        </div>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
</body>
</html>
