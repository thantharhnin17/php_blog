<?php

  session_start();
  require '../config/config.php';
  require "../config/common.php";  

  if(empty($_SESSION['user_id']) && empty($_SESSION['logged_in'])){
    header('Location: login.php');
  }
  if($_SESSION['role'] != 1){
    header('Location: login.php');
  }

  if($_POST){
    if(empty($_POST['title']) || empty($_POST['content'])){
      if(empty($_POST['title'])){
        $titleError = 'Please fill title';
      }
      if(empty($_POST['content'])){
        $contentError = 'Please fill content';
      }
    }else{
      $id = $_POST['id'];
      $title = $_POST['title'];
      $content = $_POST['content'];

      if($_FILES['image']['name'] != null){
        $file = 'images/'.($_FILES['image']['name']);
        $imageType = pathinfo($file,PATHINFO_EXTENSION);

        if($imageType != 'png' && $imageType != 'jpg' && $imageType != 'jpeg'){
            echo "<script>alert('Image must be png,jpg,jpeg.')</script>";
        }else{
            $image = $_FILES['image']['name'];
            move_uploaded_file($_FILES['image']['tmp_name'],$file);

            $stmt = $pdo->prepare("UPDATE posts SET title='$title',content='$content',image='$image' WHERE id='$id'");
            $result = $stmt->execute();
            if($result){
            echo "<script>alert('Successfully Updated'); window.location.href='index.php';</script>";
            }
        }
      }else{
            $stmt = $pdo->prepare("UPDATE posts SET title='$title',content='$content' WHERE id='$id'");
            $result = $stmt->execute();
            if($result){
            echo "<script>alert('Successfully Updated'); window.location.href='index.php';</script>";
            }
      }

    }
  }

  $stmt = $pdo->prepare("SELECT * FROM posts WHERE id=".$_GET['id']);
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
                  <!-- csrf -->
                  <input name="_token" type="hidden" value="<?php echo $_SESSION['_token']; ?>">  

                    <input type="hidden" name="id" value="<?php echo $result[0]['id'] ?>">
                    <div class="mb-3">
                        <label for="title" class="form-label">Title</label>
                        <input type="text" class="form-control" name="title" value="<?php echo $result[0]['title'] ?>">
                        <div class="form-text text-danger"><?php echo empty($titleError) ? '': '*'.$titleError; ?></div>
                    </div>
                    <div class="mb-3">
                        <label for="content" class="form-label">Content</label>
                        <textarea name="content" class="form-control" cols="30" rows="10"><?php echo $result[0]['content'] ?></textarea>
                        <div class="form-text text-danger"><?php echo empty($contentError) ? '': '*'.$contentError; ?></div>
                    </div>
                    <div class="form-group">
                        <label for="image">Image</label><br>
                        <img src="images/<?php echo $result[0]['image'] ?>" width="150px" height="150px" alt=""><br><br>
                        <input type="file" name="image" value="">
                    </div>
                    <div class="form-group">
                    <button type="submit" class="btn btn-success">Submit</button>
                    <a href="index.php" class="btn btn-warning">Back</a>
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
