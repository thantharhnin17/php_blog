<?php

  session_start();
  require 'config/config.php';
  require 'config/common.php';

  if(empty($_SESSION['user_id']) && empty($_SESSION['logged_in'])){
    header('Location: login.php');
  }

  if(!empty($_GET['pageno'])){
    $pageno = $_GET['pageno'];
  }else{
    $pageno = 1;
  }
  $numOfRecs = 6;
  $offset = ($pageno - 1) * $numOfRecs;

  $stmt = $pdo->prepare("SELECT * FROM posts ORDER BY id DESC");
  $stmt->execute();
  $raw_result = $stmt->fetchAll();
  $total_pages = ceil(count($raw_result) / $numOfRecs);

  $stmt = $pdo->prepare("SELECT * FROM posts ORDER BY id DESC LIMIT $offset,$numOfRecs");
  $stmt->execute();
  $result = $stmt->fetchAll();                

?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Blog Site</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
  <!-- Google Font: Source Sans Pro -->
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">
  

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper" style="margin-left: 0; !important">
    <!-- Content Header (Page header) -->
    <section class="content-header  py-4">
      <div class="container-fluid text-center">
        <h1>Blog Site</h1>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="container content">
        <div class="row">
          <?php
              if($result){
                        
                  foreach($result as $value){
            ?>

            <div class="col-md-4">
              <!-- Box Comment -->
              <div class="card card-widget">
                <div class="card-header">
                  <div class="card-title text-center float-none">
                    <h4><?php echo escape($value['title']) ?></h4>
                  </div>
                  
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                  <a href="blog_detail.php?id=<?php echo $value['id'] ?>">
                    <img class="img-fluid pad" 
                    src="admin/images/<?php echo $value['image'] ?>" 
                    style="width:450px; height: 250px; object-fit: cover; !important"
                    alt="Photo">
                  </a>
                </div>
                <!-- /.card-body -->
                
              </div>
              <!-- /.card -->
            </div>
            <!-- /.col -->
                       
            <?php
               }
              }
            ?>
           
        </div>
        <div class="row">
          <div class="col">
            <nav aria-label="Page navigation example" class="float-right">
                  <ul class="pagination">
                    <li class="page-item">
                      <a class="page-link" href="?pageno=1">
                        First
                      </a>
                    </li>
                    <li class="page-item <?php if($pageno <= 1){ echo 'disabled';} ?>">
                      <a class="page-link" href="<?php if($pageno <= 1){ echo '#';}else{ echo "?pageno=".($pageno-1); } ?>">
                        Previous
                      </a>
                    </li>
                    <li class="page-item">
                      <a class="page-link" href="#">
                        <?php echo $pageno; ?>
                      </a>
                    </li>
                    <li class="page-item <?php if($pageno >= $total_pages){ echo 'disabled';} ?>">
                      <a class="page-link" href="<?php if($pageno >= $total_pages){ echo '#';}else{ echo "?pageno=".($pageno+1); } ?>">
                        Next
                      </a>
                    </li>
                    <li class="page-item">
                      <a class="page-link" href="?pageno=<?php echo $total_pages ?>">
                        Last
                      </a>
                    </li>
                  </ul>
              </nav>
          </div>
        </div>
    </section>
    <!-- /.content -->

    <a id="back-to-top" href="#" class="btn btn-primary back-to-top" role="button" aria-label="Scroll to top">
      <i class="fas fa-chevron-up"></i>
    </a>
  </div>
  <!-- /.content-wrapper -->

  <footer class="main-footer" style=" margin-left:0; !important">
  <div class="float-right d-none d-sm-inline">
      <a href="logout.php" type="button" class="btn btn-default">Logout</a>
    </div>
    <strong>Copyright &copy; 2022 <a href="#">A Programmer</a>.</strong> All rights reserved.
  </footer>

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->

<!-- jQuery -->
<script src="plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/adminlte.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="dist/js/demo.js"></script>
</body>
</html>
