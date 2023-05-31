<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="model/asset/css/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css">
    <title>Sign Up</title>
    <style>
        .warn{
            background-color: #E6B325;
            margin-top: 0;
            margin-left: 10px;
            text-align: center;
            border-radius: 25px;
            margin-bottom: 20px;
        }
    </style>
  

</head>
<body>
    <div class="wrapper">
        <div class="container main">
            <div class="row">
                <div class="col-md-6 side-image">
                    <div class="header">KriptoTalks<br></div>
                    <!-------Image-------->
                    <img src="model/asset/img/cover_login.png" width="400" height="100" alt="">
                    <div class="text">
                        
                    </div>
                </div>
                <div class="col-md-6 right">
                     <div class="input-box">
                        <h2 class="mb-5 text-center"><b>CREATE ACCOUNT</b></h2>
                        <form action="controller/register.php" method="POST" enctype="multipart/form-data">
                            <div class="input-field">
                                <input type="text" class="input" id="email" name="email" required autocomplete="off">
                                <label for="email">Email</label>
                            </div>
                            <!-- nama -->
                            <div class="input-field">
                                <input type="text" class="input" id="nama" name="nama" required autocomplete="off">
                                <label for="text">Nama</label>
                            </div>
                            <div class="input-field">
                                <input type="text" class="input" id="username" name="username" required autocomplete="off">
                                <label for="text">Username</label>
                            </div>
                            <!-- pw --> 
                            <div class="input-field">
                                <input type="password" class="input" id="password"  name="password" required>
                                <label for="password">Password</label>
                            </div>
                            <div class="mx-3">
                                <label for="password">Foto Profil</label>
                                <input class="input" id="foto" name="foto" type="file" >
                            </div>
                            <div class="warn">
                                <b>Maksimal foto 1 mb !!</b>
                            </div>
                            <!-- status -->
                            <div class="input-field">
                                <input type="submit" name="submit" class="submit" value="Sign Up">
                                
                            </div>
                            <div class="signin">
                                <span>Already have an account? <a href="login.php">Log in here</a></span><br>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>