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

  if(isset($_POST['search'])){
    setcookie('search', $_POST['search'], time() + (86400 * 30), "/"); // 86400 = 1 day
  }else{
    if(empty($_GET['pageno'])){
      unset($_COOKIE['search']); 
      setcookie('search', null, -1, '/'); 
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
                <h3 class="card-title">User Listings</h3>
              </div>

              <?php

                if(!empty($_GET['pageno'])){
                  $pageno = $_GET['pageno'];
                }else{
                  $pageno = 1;
                }
                $numOfRecs = 5;
                $offset = ($pageno - 1) * $numOfRecs;

                if(empty($_POST['search']) && empty($_COOKIE['search'])){
                  $stmt = $pdo->prepare("SELECT * FROM users ORDER BY id DESC");
                  $stmt->execute();
                  $raw_result = $stmt->fetchAll();
                  $total_pages = ceil(count($raw_result) / $numOfRecs);

                  $stmt = $pdo->prepare("SELECT * FROM users ORDER BY id DESC LIMIT $offset,$numOfRecs");
                  $stmt->execute();
                  $result = $stmt->fetchAll();
                }else{
                  $search_key = isset($_POST['search']) ? $_POST['search'] : $_COOKIE['search'];
                  $stmt = $pdo->prepare("SELECT * FROM users WHERE name LIKE '%$search_key%' ORDER BY id DESC");
                  $stmt->execute();
                  $raw_result = $stmt->fetchAll();
                  $total_pages = ceil(count($raw_result) / $numOfRecs);

                  $stmt = $pdo->prepare("SELECT * FROM users WHERE name LIKE '%$search_key%' ORDER BY id DESC LIMIT $offset,$numOfRecs");
                  $stmt->execute();
                  $result = $stmt->fetchAll();
                }
              ?>

              <!-- /.card-header -->
              <div class="card-body">
                <div>
                  <a href="user_add.php" class="btn btn-success">Create New User</a>
                </div>
                <br>
                <table class="table table-bordered">
                  <thead>                  
                    <tr>
                      <th style="width: 10px">#</th>
                      <th>Name</th>
                      <th>Email</th>
                      <th>Role</th>
                      <th style="width: 150px">Actions</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                      if($result){
                        if(!empty($_GET['pageno'])){
                          $i = $offset+1;
                        }else{
                          $i = 1;
                        }
                        
                        foreach($result as $value){
                    ?>

                    <tr>
                      <td><?php echo $i; ?></td>
                      <td><?php echo escape($value['name']); ?></td>
                      <td><?php echo escape($value['email']); ?></td>
                      <td><?php if($value['role'] == 0){
                        echo "user";
                      }else{
                        echo "admin";
                      } ?></td>
                      <td>
                        <a href="user_edit.php?id=<?php echo $value['id']; ?>" class="btn btn-sm btn-warning">Edit</a>
                        <a href="user_delete.php?id=<?php echo $value['id']; ?>" 
                          class="btn btn-sm btn-danger"
                          onclick="return confirm('Are you sure you want to delete this user?');">
                          Delete
                        </a>
                      </td>
                    </tr>
                       
                    <?php
                    $i++;
                        }
                      }
                    ?>
                    
                  </tbody>
                </table>
                      <br>
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
              <!-- /.card-body -->
              
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
