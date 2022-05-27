<?php

  session_start();
  require '../config/config.php';

  if(empty($_SESSION['user_id']) && empty($_SESSION['logged_in'])){
    header('Location: login.php');
  }

  if($_POST){
    $name = $_POST['name'];
    $email = $_POST['email'];
    $role = $_POST['role'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email=:email");

    $stmt->bindValue(':email',$email);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if($user){
        echo "<script>alert('Email duplicate')</script>";
    }else{  
      $stmt = $pdo->prepare("INSERT INTO users(name,email,role) VALUES (?,?,?)");
      $result = $stmt->execute([$name,$email,$role]);
      if($result){
        echo "<script>alert('Successfully Register As A User!'); window.location.href='user.php';</script>";
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
                        <input type="text" class="form-control" name="name" value="" required>
                        <!-- <div id="emailHelp" class="form-text">We'll never share your email with anyone else.</div> -->
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="text" class="form-control" name="email" value="" required>
                        <!-- <div id="emailHelp" class="form-text">We'll never share your email with anyone else.</div> -->
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="role" id="user" value="0">
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
