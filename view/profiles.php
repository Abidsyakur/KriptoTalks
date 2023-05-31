<?php
include_once('../config/koneksi.php');
session_start();

if (isset($_GET['id'])) {
    $id_users = $_GET['id'];
    $data = mysqli_query($conn, "SELECT * FROM users WHERE id_users = '$id_users'");
    $result = mysqli_fetch_array($data);
} else {  
    // Redirect ke halaman lain jika ID pengguna tidak ditemukan
    header("Location: error.php");
    exit();
}

$id_user = $_SESSION['id'];
$sql = mysqli_query($conn, "SELECT * FROM users WHERE id_users = '$id_user'");
$hsl = mysqli_fetch_array($sql);

// Query untuk mengambil data post
$query = "SELECT * FROM post INNER JOIN users ON post.id_users = users.id_users WHERE users.id_users = $id_users ORDER BY post.id_users DESC";
$hasil = mysqli_query($conn, $query);

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

?>

<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Bootstrap demo</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
  <link rel="stylesheet" href="../model/asset/css/style2.css">
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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
              <img src="../model/upload/profile/<?=$hsl['foto']?>"  width="50" height="50" alt="foto" class="rounded-circle">
            </div>
            <div class="mx-3">
              <h4 class="text-white m-0"><?=$hsl['nama']?></h4>
              <p class="text-white m-0">@<?=$hsl['username']?></p>
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
              <?php
              if (isset($_SESSION['id'])) {
                $id_user = $_SESSION['id'];
              
                if ($_SESSION['id'] != $id_users) {
                  $following_id = $_SESSION['id'];
                  $followers_id = $id_users;
                  $query = "SELECT * FROM followers WHERE following_id_users = '$following_id' AND followers_id_users = '$followers_id'";
                  $result = mysqli_query($conn, $query);
                  $isFollowing = mysqli_num_rows($result) > 0;
              ?>
                  <button class="follow-btn" data-userid="<?=$id_users?>" data-following="<?=$isFollowing ? 'true' : 'false'?>">
                    <?=$isFollowing ? 'Berhenti Mengikuti' : 'Ikuti'?>
                  </button>
              <?php
                }
              } else {
                // Jika pengguna tidak masuk, set jumlah pengikut dan yang diikuti ke 0
                $totalFollowers = 0;
                $totalFollowing = 0;
              }
              ?>

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
                      <form action=""></form>
                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                      <button type="button" class="btn btn-primary">Save changes</button>
                    </div>
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
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
  </div>

  <script>
    $(document).ready(function() {
      $('.follow-btn').on('click', function(e) {
        e.preventDefault();
        var userId = $(this).data('userid');
        var isFollowing = $(this).data('following') === 'true';
        var button = $(this);

        $.ajax({
          type: 'GET',
          url: '../controller/follow.php?user_id=' + userId,
          success: function(response) {
            if (isFollowing) {
              button.text('Ikuti');
              button.data('following', 'false');
            } else {
              button.text('Berhenti Mengikuti');
              button.data('following', 'true');
            }
            alert(response);
          },
          error: function(xhr, status, error) {
            console.error(xhr.responseText);
          }
        });
      });
    });
  </script>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
</body>
</html>
