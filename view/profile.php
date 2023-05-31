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
$query = "SELECT * FROM post INNER JOIN users ON post.id_users = users.id_users WHERE post.id_users = '$id_users' ";
$hasil = mysqli_query($conn, $query);

if (isset($_SESSION['id'])) {
    $id_users = $_SESSION['id'];

    // Menghitung jumlah pengikut (followers)
    $followersQuery = "SELECT COUNT(*) as total_followers FROM followers WHERE followers_id_users = '$id_users'";
    $followersResult = mysqli_query($conn, $followersQuery);
    $followersData = mysqli_fetch_assoc($followersResult);
    $totalFollowers = $followersData['total_followers'];

    // Menghitung jumlah yang diikuti (following)
    $followingQuery = "SELECT COUNT(*) as total_following FROM followers WHERE following_id_users = '$id_users'";
    $followingResult = mysqli_query($conn, $followingQuery);
    $followingData = mysqli_fetch_assoc($followingResult);
    $totalFollowing = $followingData['total_following'];
} else {
    // Jika pengguna tidak masuk, set jumlah pengikut dan yang diikuti ke 0
    $totalFollowers = 0;
    $totalFollowing = 0;
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
  .follower{
    margin-bottom: 3rem;
    margin-top: 2rem;
  }
  .modal-content{
  background-color: #1e1e1e;
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
                <img src="../model/upload/profile/<?=$result['foto']?>"  width="50" height="50" alt="foto" class="rounded-circle">
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
          </a>
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
                  <!-- bagian post -->
                  <div class="input-area">
  <div class="profile-picture">
    <img src="../model/upload/profile/<?=$result['foto']?>" width="50" height="50" alt="foto" class="rounded-circle">
  </div>
  <div class="input-wrapper">
    <div class="prof">
        <h2><b><?=$result['nama']?></b></h2>
    </div>
    <div class="username" style="color:grey">
        <h5>@<?=$result['username']?></h5>
    </div>
    <div class="follower">
        <div class="row">
            <div class="col">
                Followers
                <p><?=$totalFollowers?></p>
            </div>
            <div class="col">
                Following
                <p><?=$totalFollowing?></p>
            </div> 
        </div> 
    </div>
    </div> 

    <div class="edit">
        <!-- Button trigger modal -->
<button type="button" class="btn btn-dark" data-bs-toggle="modal" data-bs-target="#exampleModal">
  Edit
</button>

<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Edit Profile</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form action="../controller/updateuser.php" method="post" enctype="multipart/form-data">
          <div class="mb-3">
          <label for="exampleInputEmail1" class="form-label">Username</label>
          <input type="text" class="form-control" id="username" name="username" aria-describedby="emailHelp" value="<?=$result['username']?>">
          </div>
          <div class="mb-3">
          <label for="exampleInputEmail1" class="form-label">Nama</label>
          <input type="text" class="form-control" id="nama" name="nama" aria-describedby="emailHelp" value="<?=$result['nama']?>">
          </div>
          <div class="mb-3">
          <label for="exampleInputEmail1" class="form-label">Email</label>
          <input type="email" class="form-control" id="email" name="email" aria-describedby="emailHelp" value="<?=$result['email']?>">
          </div>
           <div class="mb-3">
          <label for="exampleInputPassword1" class="form-label">Password</label>
          <input type="password" class="form-control" id="password" name="password" value="<?=$result['password']?>">
           </div>
           <div class="mb-3">
            <label for="formFile" class="form-label">Foto</label>
            <input class="form-control" type="file" id="foto" name="foto" value="<?=$result['foto']?>">
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="submit" name="submit" class="btn btn-primary">Save changes</button>
        </div>
      </form>
    </div>
  </div>
</div>
    </div>
</div>

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
           <a href="../controller/delpost.php?id_post=<?=$row['id_post']?>"> <button type="button" class="btn btn-dark"style="float:right;top:0;margin-top:4px;">Delete</button></a>
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
