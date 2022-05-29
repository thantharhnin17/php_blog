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
    $id = $_POST['id'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $role = $_POST['role'];
    if(empty($name) || empty($email) || empty($password) || strlen($password) < 4){
      if(empty($name)){
        $nameError = 'Please fill name';
      }
      if(empty($email)){
        $emailError = 'Please fill email';
      }
      if(empty($password)){
        $passwordError = 'Please fill password';
      }
      elseif(strlen($password) < 4){
        $passwordError = 'Password should be 4 characters at least';
      }
    }else{
      $stmt = $pdo->prepare("SELECT * FROM users WHERE email=:email AND id!=:id");

      $stmt->execute(array(':email'=>$email,':id'=>$id));
      $user = $stmt->fetch(PDO::FETCH_ASSOC);

      if($user){
          echo "<script>alert('Email duplicate')</script>";
      }else{ 
              $stmt = $pdo->prepare("UPDATE users SET name='$name',email='$email',password='$password',role='$role' WHERE id='$id'");
              $result = $stmt->execute();
              if($result){
              echo "<script>alert('Successfully Updated'); window.location.href='user.php';</script>";
              }
          }
    }

  }

  $stmt = $pdo->prepare("SELECT * FROM users WHERE id=".$_GET['id']);
  $stmt->execute();

  $result = $stmt->fetchAll();
//   print"<pre>";
//   print_r($result[0]['id']);

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
                <h3 class="card-title">Update Post</h3>
              </div>

              <div class="card-body">
                <form action="" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="id" value="<?php echo $result[0]['id'] ?>">
                    <div class="mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" class="form-control" name="name" value="<?php echo $result[0]['name'] ?>">
                        <div class="form-text text-danger"><?php echo empty($nameError) ? '': '*'.$nameError; ?></div>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="text" class="form-control" name="email" value="<?php echo $result[0]['email'] ?>">
                        <div class="form-text text-danger"><?php echo empty($emailError) ? '': '*'.$emailError; ?></div>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" name="password" id="pass" value="<?php echo $result[0]['password'] ?>">
                        <div class="form-text text-danger"><?php echo empty($passwordError) ? '': '*'.$passwordError; ?></div>
                        <div class="form-group form-check">
                          <input type="checkbox" class="form-check-input" id="checkPass" onclick="showPassword()">
                          <label class="form-check-label" for="checkPass">Show Password</label>
                        </div>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="role" id="user" value="0" <?php if($result[0]['role']==0) echo "checked" ?>>
                        <label class="form-check-label" for="user">User</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="role" id="admin" value="1" <?php if($result[0]['role']==1) echo "checked" ?>>
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
