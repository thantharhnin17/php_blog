<?php

    session_start();
    require 'config/config.php';
    require 'config/common.php';

    if($_POST){
        
        if(empty($_POST['name']) || empty($_POST['email']) || empty($_POST['password']) || strlen($_POST['password']) < 4){
          if(empty($_POST['name'])){
            $nameError = 'Please fill name';
          }
          if(empty($_POST['email'])){
            $emailError = 'Please fill email';
          }
          if(empty($_POST['password'])){
            $passwordError = 'Please fill password';
          }
          elseif(strlen($_POST['password']) < 4){
            $passwordError = 'Password should be 4 characters at least';
          }
        }else{

          $name = $_POST['name'];
          $email = $_POST['email'];
          $password = password_hash($_POST['password'],PASSWORD_DEFAULT);
          $role = 0;

          $stmt = $pdo->prepare("SELECT * FROM users WHERE email=:email");

          $stmt->bindValue(':email',$email);
          $stmt->execute();
          $user = $stmt->fetch(PDO::FETCH_ASSOC);

          if($user){
              echo "<script>alert('Email duplicate')</script>";
          }else{  
            $stmt = $pdo->prepare("INSERT INTO users(name,email,password,role) VALUES (?,?,?,?)");
            $result = $stmt->execute([$name,$email,$password,$role]);
            if($result){
              echo "<script>alert('Successfully Register! You can now log in.'); window.location.href='login.php';</script>";
            }
          }

        }


    }

?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Blog | Register</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Font Awesome -->
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- icheck bootstrap -->
  <link rel="stylesheet" href="plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
  <!-- Google Font: Source Sans Pro -->
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
</head>
<body class="hold-transition login-page">
<div class="login-box">
  <div class="login-logo">
    <a href="#"><b>Blog</b>User</a>
  </div>
  <!-- /.login-logo -->
  <div class="card">
    <div class="card-body login-card-body">
      <p class="login-box-msg">Register new account</p>

      <form action="register.php" method="post">
        <!-- csrf -->
        <input name="_token" type="hidden" value="<?php echo $_SESSION['_token']; ?>"> 

        <div class="mb-3">
          <div class="form-text text-danger"><?php echo empty($nameError) ? '': '*'.$nameError; ?></div>
          <div class="input-group">
            <input type="name" name="name" class="form-control" placeholder="Name"  value="<?php echo escape($_POST['name'] ?? ''); ?>">
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-user"></span>
              </div>
            </div>
          </div>
        </div>
        <div class="mb-3">
          <div class="form-text text-danger"><?php echo empty($emailError) ? '': '*'.$emailError; ?></div>
          <div class="input-group">
            <input type="email" name="email" class="form-control" placeholder="Email"  value="<?php echo escape($_POST['email'] ?? ''); ?>">
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-envelope"></span>
              </div>
            </div>
          </div>
        </div>
        <div class="mb-3">
          <div class="form-text text-danger"><?php echo empty($passwordError) ? '': '*'.$passwordError; ?></div>
          <div class="input-group">
            <input type="password" name="password" class="form-control" id="pass" placeholder="Password"  value="<?php echo escape($_POST['password'] ?? ''); ?>">
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-lock"></span>
              </div>
            </div>
          </div>
          <div class="form-group-inline form-check">
              <input type="checkbox" class="form-check-input" id="checkPass" onclick="showPassword()">
              <label class="form-check-label" for="checkPass">Show Password</label>
          </div>
        </div>
          
          <div class="row">
            <div class="col-6">
                <button type="submit" class="btn btn-primary btn-block">Register</button>
            </div>
            <div class="col-6">
                <a href="login.php" class="btn btn-default btn-block">Log In</a>
            </div>
          </div>
      </form>

      <!-- <p class="mb-0">
        <a href="register.html" class="text-center">Register a new membership</a>
      </p> -->
    </div>
    <!-- /.login-card-body -->
  </div>
</div>
<!-- /.login-box -->

<script>
    function showPassword() {
      var x = document.getElementById("pass");
      if (x.type === "password") {
        x.type = "text";
      } else {
        x.type = "password";
      }
    }
  </script>

<!-- jQuery -->
<script src="plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/adminlte.min.js"></script>

</body>
</html>
