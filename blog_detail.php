<?php

  session_start();
  require 'config/config.php';
  require 'config/common.php';

  if(empty($_SESSION['user_id']) && empty($_SESSION['logged_in'])){
    header('Location: login.php');
  }

  // echo $_SESSION['user_id'];
  // exit;

  $stmt = $pdo->prepare("SELECT * FROM posts WHERE id=".$_GET['id']);
  $stmt->execute();
  $result = $stmt->fetchAll();

  $post_id=$_GET['id'];

  $cmt_stmt = $pdo->prepare("SELECT * FROM comments WHERE post_id=".$_GET['id']);
  $cmt_stmt->execute();
  $cmt_result = $cmt_stmt->fetchAll();

  $au_result = [];
  if($cmt_result){
    foreach($cmt_result as $key => $value){
      $author_id = $cmt_result[$key]['author_id'];
      $au_stmt = $pdo->prepare("SELECT * FROM users WHERE id=".$author_id);
      $au_stmt->execute();
      $au_result[] = $au_stmt->fetchAll();
    }
  }

  if($_POST){
    $comment = $_POST['comment'];  
    if(empty($comment)){
      if(empty($comment)){
        $cmtError = 'Please fill comment';
      }
    }else{
      $stmt = $pdo->prepare("INSERT INTO comments(content,author_id,post_id) VALUES (?,?,?)");
      $result = $stmt->execute([$comment,$_SESSION['user_id'],$post_id]);

      if($result){
        header('Location: blog_detail.php?id='.$post_id);
      }

    }

  }

?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Blog | Blog Details</title>
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
  <div class="container mt-4">

    <!-- Main content -->
    <section class="content" style="margin-left: 0; !important">
        <div class="row">
            <div class="col">
              <!-- Box Comment -->
              <div class="card card-widget">
                <div class="card-header">
                  <div class="card-title text-center float-none">
                    <h3><?php echo escape($result[0]['title']) ?></h3>
                  </div>
                  
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                  <img class="img-fluid pad" src="admin/images/<?php echo $result[0]['image'] ?>" alt="Photo">
                  <br><br>
                  <p><?php echo escape($result[0]['content']) ?></p>
                </div>

                <div class="container-fluid border-bottom pb-2 px-4">
                  <h4 class="text-muted d-inline">Comments</h4>
                  <a href="index.php" class="btn btn-secondary float-right">Go Back To Home</a>
                </div>
                <!-- /.card-body -->
                <div class="card-footer card-comments">
                <?php if($cmt_result){ 
                      foreach($cmt_result as $key => $value ){?>
                  <div class="card-comment">
                    <!-- User image -->
                    <div class="rounded-circle bg-dark text-white text-center float-left" style="width:30px; height:30px;">
                      <span class="fas fa-user" style="margin-top: 5px;"></span>
                    </div>
                   
                      <div class="comment-text">
                        <span class="username">
                          <?php echo escape($au_result[$key][0]['name']); ?>
                          <span class="text-muted float-right"><?php echo escape($value['created_at']); ?></span>
                        </span><!-- /.username -->
                        <?php echo escape($value['content']); ?>
                      </div>

                    <!-- /.comment-text -->
                  </div>
                  <!-- /.card-comment -->
                  
                  <?php } } ?>
                </div>
                <!-- /.card-footer -->
                <div class="card-footer">
                  <form action="" method="post">
                      <!-- csrf -->
                      <input name="_token" type="hidden" value="<?php echo $_SESSION['_token']; ?>"> 

                      <div class="rounded-circle bg-dark text-white text-center float-left mr-2" style="width:30px; height:30px;">
                        <span class="fas fa-user" style="margin-top: 5px;"></span>
                      </div>
                  <!-- .img-push is used to add margin to elements next to floating images -->
                    <div class="img-push" style="width:95%; display:inline-block; !important">

                      <input type="text" name="comment" class="form-control form-control-sm" placeholder="Press enter to post comment">
                      <div class="text-danger"><?php echo empty($cmtError) ? '': '*'.$cmtError; ?></div>
                    </div>
                  </form>
                </div>
                <!-- /.card-footer -->
              </div>
              <!-- /.card -->
            </div>
            <!-- /.col -->
            
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
