<?php

  session_start();
  require '../config/config.php';

  if(empty($_SESSION['user_id']) && empty($_SESSION['logged_in'])){
    header('Location: login.php');
  }
  if($_SESSION['role'] != 1){
    header('Location: login.php');
  }

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
      $role = $_POST['role'];

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
          echo "<script>alert('Successfully Register As A User!'); window.location.href='user.php';</script>";
        }
      }

    }

}

?>

<?php
  include 'header.php';
?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Create New User</h3>
              </div>

              <div class="card-body">
                <form action="user_add.php" method="post" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" class="form-control" name="name" value="">
                        <div class="form-text text-danger"><?php echo empty($nameError) ? '': '*'.$nameError; ?></div>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="text" class="form-control" name="email" value="">
                        <div class="form-text text-danger"><?php echo empty($emailError) ? '': '*'.$emailError; ?></div>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" name="password" id="pass" value="">
                        <div class="form-text text-danger"><?php echo empty($passwordError) ? '': '*'.$passwordError; ?></div>
                        <div class="form-group form-check">
                          <input type="checkbox" class="form-check-input" id="checkPass" onclick="showPassword()">
                          <label class="form-check-label" for="checkPass">Show Password</label>
                        </div>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="role" id="user" value="0" checked>
                        <label class="form-check-label" for="user">User</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="role" id="admin" value="1">
                        <label class="form-check-label" for="admin">Admin</label>
                    </div>
                    <br><br>
                    <div class="form-group">
                        <button type="submit" class="btn btn-success">Submit</button>
                        <a href="user.php" class="btn btn-warning">Back</a>
                    </div>
                </form>
              </div>
              
            </div>
            <!-- /.card -->

            
          </div>
        </div>
        <!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

<?php include 'footer.php' ?>
