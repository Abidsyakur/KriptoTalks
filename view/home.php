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
$query = "SELECT * FROM post INNER JOIN users ON post.id_users = users.id_users ORDER BY post.tgl_post DESC";
$hasil = mysqli_query($conn, $query);

//topics
function getTrendingKategori()
{
    global $conn;

    $sql = "SELECT nama_kategori, COUNT(*) as total FROM kategori
              GROUP BY nama_kategori
              ORDER BY total DESC";
    $result = mysqli_query($conn, $sql);

    if (!$result) {
        die("Error executing query: " . mysqli_error($conn));
    }

    return $result;
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
  #image-preview img {
    max-width: 150px; /* Sesuaikan dengan ukuran yang diinginkan */
    max-height: 150px; /* Sesuaikan dengan ukuran yang diinginkan */
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
        <a href="profile.php"style="text-decoration:none;color:white;cursor:pointer;">
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
          <h2>Home</h2>
                  <!-- bagian post -->
                  <div class="input-area">
  <div class="profile-picture">
    <img src="../model/upload/profile/<?=$result['foto']?>" width="50" height="50" alt="foto" class="rounded-circle">
  </div>
  <div class="input-wrapper">
  <form action="../controller/addpost.php" method="POST" enctype="multipart/form-data">
    <label for="judul">Judul:</label>
    <input type="text" id="judul" name="judul">
    <textarea placeholder="Apa yang sedang Anda pikirkan?" id="konten" name="konten"></textarea>
    <div class="add-photo">
      <label for="photo-upload" class="add-photo-label">Tambah Foto</label>
      <input type="file" id="photo-upload" class="input" name="gambar" accept="image/*" onchange="previewImage(event)">
      <div id="image-preview"></div>
      <div class="kategori">
        <select name="kategori" id="kategori">
          <option selected>Pilih kategori</option>
          <option value="btc">BTC</option>
          <option value="etc">ETH</option>
          <option value="xrp">XRP</option>
          <option value="sol">SOL</option>
          <option value="lainnya">Lainnya</option>
        </select>
      </div>
    </div>
  </form>
</div>



    <button class="post-button" type="submit">Post</button>
  </form>
</div>
        <?php foreach($hasil as $row): ?>
          <div class="post">
            <div class="prof mb-2">
            <a href="profiles.php?id=<?=$row['id_users']?>">
              <img src="../model/upload/profile/<?=$row['foto']?>" width="50" height="50" alt="foto" class="rounded-circle">
              <b><?=$row['nama']?></b><span style="color: white;opacity:0.5">@<?=$row['username']?></span>
            </a>
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
      <div class="col-md-3">
    <!-- Bagian kanan -->
    <div class="trending">
        <h3>Trending Topics</h3>
        <ul>
            <?php
            $kategori = getTrendingKategori();
            while ($row = mysqli_fetch_assoc($kategori)) {
                echo "<li>" . $row['nama_kategori'] . "</li>";
            }
            ?>
        </ul>
    </div>
</div>
    </div>
  </div>
  <script>
  function previewImage(event) {
    var reader = new FileReader();
    reader.onload = function () {
      var output = document.getElementById('image-preview');
      output.innerHTML = '<img src="' + reader.result + '" alt="Preview Gambar">';
    }
    reader.readAsDataURL(event.target.files[0]);
  }
</script>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
</body>
</html>
